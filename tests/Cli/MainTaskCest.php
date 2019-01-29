<?php

declare(strict_types=1);

class MainTaskCest
{
    public function mainTest(CliTester $I): void
    {
        $I->wantToTest('main task output');
        $I->runShellCommand('php app/cli.php');
        $I->seeInShellOutput(
            "Run 'seed user <username> <password>' or 'seed everything'"
        );
    }
}
