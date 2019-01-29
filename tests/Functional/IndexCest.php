<?php

declare(strict_types=1);

class IndexCest
{
    public function indexTest(\FunctionalTester $I): void
    {
        $I->wantTo('open index page');
        $I->amOnPage('/');
        $I->see('Nothing of particular interest here.');
        $I->seeResponseCodeIs(200);
    }
}
