<?php
/**
 * Created by PhpStorm.
 * User: isbb 110
 * Date: 2/4/2019
 * Time: 5:26 PM
 */

namespace App\Controller;

use App\Entity\Article;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var bool
     */
    private $isDebug;


    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        $articles = $repository->findBy([], ['publishedAt' => 'DESC']);

        return $this->render(
            "article/homepage.html.twig",
            [
                'articles' => $articles,
            ]
        );
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug, SlackClient $slackClient, EntityManagerInterface $em)
    {
        if ($slug == "Luci") {
            $slackClient->sendMessage('John Doe', 'This is an amazing message!');
        }

        $repository = $em->getRepository(Article::class);
        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render(
            'article/show.html.twig',
            [
                'article' => $article,
                'comments' => $comments,
            ]
        );
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(LoggerInterface $logger)
    {
        // TODO - acutally heart/unheart the article!
        $logger->info('Execute toggleArticleHeart');

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}