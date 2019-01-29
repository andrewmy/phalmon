<?php

declare(strict_types=1);

use Phalcon\Cli\Task;

/**
 * @property \Phalcon\Queue\Beanstalk    $queue
 * @property \App\Service\MessageHandler $messageHandler
 */
class QueueTask extends Task
{
    public function mainAction(): void
    {
        while (false !== ($job = $this->queue->reserve())) {
            $message = $job->getBody();

            if (
                isset(
                    $message['createMessage']['userId'],
                    $message['createMessage']['content']
                )
            ) {
                $this->messageHandler->create(
                    $message['createMessage']['userId'],
                    $message['createMessage']['content']
                );
            }

            $job->delete();
        }
    }
}
