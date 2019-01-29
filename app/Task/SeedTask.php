<?php

declare(strict_types=1);

use App\Model\Message;
use Phalcon\Cli\Task;

/**
 * @property \Phalcon\Logger\AdapterInterface $logger
 * @property \App\Factory\UserFactory         $userFactory
 */
class SeedTask extends Task
{
    public function mainAction(): void
    {
        echo "Run 'seed user <username> <password>' or 'seed everything'".PHP_EOL;
    }

    public function userAction(array $params): void
    {
        if (2 !== \count($params)) {
            echo "Run 'seed user <username> <password>'".PHP_EOL;

            return;
        }

        $user = $this->userFactory->create($params[0], $params[1]);
        $result = $user->save();

        if ($result) {
            $message = \sprintf(
                'Seeded user %s, id = %s', $params[0], $user->getId()
            );
            $this->logger->info($message);
            echo $message.PHP_EOL;

            return;
        }

        $message = \sprintf(
            'Failed to seed user %s: %s',
            $params[0],
            \implode('; ', $user->getMessages())
        );
        $this->logger->error($message);
        echo $message.PHP_EOL;
    }

    public function everythingAction(): void
    {
        $userCount = 10;
        $messageCount = 20;

        $faker = Faker\Factory::create();

        for ($userCounter = 0; $userCounter < $userCount; ++$userCounter) {
            do {
                $password = $faker->password;
                $user = $this->userFactory->create(
                    $faker->userName, $password
                );
                $result = $user->save();
            } while (!$result);

            for ($msgCounter = 0; $msgCounter < $messageCount; ++$msgCounter) {
                $message = new Message();
                $message->userId = (string) $user->getId();
                $message->content = $faker->paragraph;
                $message->save();
            }

            $resultMessage = \sprintf(
                'Created user: %s, %s', $user->username, $password
            );
            $this->logger->info($resultMessage);
            echo $resultMessage.PHP_EOL;
        }

        $resultMessage = \sprintf(
            'Seeded %d users and %d messages',
            $userCount,
            $userCount * $messageCount
        );
        $this->logger->info($resultMessage);
        echo $resultMessage.PHP_EOL;
    }
}
