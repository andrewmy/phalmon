<?php

declare(strict_types=1);

class SeedTaskCest
{
    public function mainTest(CliTester $I): void
    {
        $I->wantToTest('seed main task output');

        $I->runShellCommand('php app/cli.php seed');

        $I->seeInShellOutput(
            "Run 'seed user <username> <password>' or 'seed everything'"
        );
    }

    public function seedUserTest(CliTester $I): void
    {
        $I->wantToTest('seed user');

        $I->runShellCommand('php app/cli.php seed user han');

        $I->seeInShellOutput("Run 'seed user <username> <password>'");

        $I->runShellCommand('php app/cli.php seed user han solo');
        $I->seeInShellOutput('Seeded user han, id =');

        $I->runShellCommand('php app/cli.php seed user han solo');
        $I->seeInShellOutput(
            'Failed to seed user han: Field username must be unique'
        );

        $I->seeInCollection('user', ['username' => 'han']);
    }

    public function seedEverythingTest(CliTester $I): void
    {
        $I->wantToTest('seed 10 users 20 messages each');

        $I->runShellCommand('php app/cli.php seed everything');

        $I->seeInShellOutput('Created user: ');
        $I->seeInShellOutput('Seeded 10 users and 200 messages');
    }
}
