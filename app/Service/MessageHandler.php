<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Message;
use Phalcon\Logger\AdapterInterface;

class MessageHandler
{
    /** @var AdapterInterface */
    private $logger;

    public function __construct(AdapterInterface $logger)
    {
        $this->logger = $logger;
    }

    public function create(string $userId, ?string $content): Message
    {
        $message = new Message();
        $message->userId = $userId;
        $message->content = $content;
        $result = $message->save();

        if ($result) {
            $this->logger->info(
                \sprintf(
                    'Created message %s from user %s',
                    $message->getId(),
                    $message->userId
                )
            );
        } else {
            $this->logger->error(
                \sprintf(
                    'Failed to create message from %s: %s',
                    $message->userId,
                    \implode('; ', $message->getMessages())
                )
            );
        }

        return $message;
    }
}
