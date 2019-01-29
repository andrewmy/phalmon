<?php

declare(strict_types=1);

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction(): void
    {
        echo "Run 'seed user <username> <password>' or 'seed everything'".PHP_EOL;
    }
}
