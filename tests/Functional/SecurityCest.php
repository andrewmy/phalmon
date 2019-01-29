<?php

declare(strict_types=1);

class SecurityCest
{
    public function badMethodTest(\FunctionalTester $I): void
    {
        $I->amOnPage('/api/login');
        $I->seeResponseCodeIs(404);
    }

    public function loginTest(\FunctionalTester $I): void
    {
        $I->wantTo('log in');
        $I->haveInCollection('user', [
            'username' => 'phteven',
            'password' => $I->getApplication()->getDI()->get('security')->hash('phan'),
        ]);

        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/api/login', ['username' => 'phteven', 'password' => 'phmoked']);
        $I->seeResponseCodeIs(401);
        $I->see('Bad credentials');

        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/api/login', ['username' => 'phteven', 'password' => 'phan']);
        $I->seeResponseCodeIs(200);
        $I->see('token');
    }
}
