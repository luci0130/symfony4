<?php
/**
 * Created by PhpStorm.
 * User: isbb 110
 * Date: 2/14/2019
 * Time: 5:03 PM
 */

namespace App\Service;

use App\Helper\LoggerTrait;
use Nexy\Slack\Client;

class SlackClient
{
    use LoggerTrait;
    /**
     * @var Client
     */
    private $slackClient;

    public function __construct(Client $slackClient)
    {
        $this->slackClient = $slackClient;
    }

    public function sendMessage(string $from, string $message)
    {
        $this->logInfo('Luci send a message', [
            'message' => $message
        ]);

        $sendMessage = $this->slackClient->createMessage();
        $sendMessage
            ->to('#casino-games')
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);

        $this->slackClient->sendMessage($sendMessage);
    }
}