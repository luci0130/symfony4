<?php
/**
 * Created by PhpStorm.
 * User: isbb 110
 * Date: 2/7/2019
 * Time: 11:48 AM
 */

namespace App\Service;


use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var MarkdownParserInterface
     */
    private $markdown;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var bool
     */
    private $isDebug;

    /**
     * MarkdownHelper constructor.
     * @param AdapterInterface $cache
     * @param MarkdownParserInterface $markdown
     */
    public function __construct(
        AdapterInterface $cache,
        MarkdownParserInterface $markdown,
        LoggerInterface $markdownLogger,
        bool $isDebug
    )
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false) {
            $this->logger->info('They are talking about bacon again!');
        }

        if($this->isDebug)
        {
            return $this->markdown->transformMarkdown($source);
        }

        $item = $this->cache->getItem('markdown_' . md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transformMarkdown($source));
            $this->cache->save($item);
        }
        return $item->get();
    }
}