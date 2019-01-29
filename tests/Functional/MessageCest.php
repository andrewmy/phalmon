<?php

declare(strict_types=1);

class MessageCest
{
    public function createTest(\FunctionalTester $I): void
    {
        $I->wantTo('create message');

        $I->logIn();

        $I->sendPOST('/api/messages', ['content' => '']);
        $I->seeResponseCodeIs(422);

        $I->sendPOST('/api/messages', ['content' => 'Whale hello!']);
        $I->seeResponseCodeIs(201);

        $I->amOnPage('/api/messages');
        $I->assertSame([1], $I->grabDataFromResponseByJsonPath('meta.request_count'));
        $I->assertSame(
            ['Whale hello!'],
            $I->grabDataFromResponseByJsonPath('data[0].attributes.content')
        );
        // checking timestampable while we're here
        $I->assertNotEmpty(
            $I->grabDataFromResponseByJsonPath('data[0].attributes.createdAt')
        );
    }

    public function listCounterTest(FunctionalTester $I): void
    {
        $I->wantTo('see message view counter');

        $I->logIn();

        $I->amOnPage('/api/messages');
        $I->assertSame([1], $I->grabDataFromResponseByJsonPath('meta.request_count'));

        $I->amOnPage('/api/messages');
        $I->assertSame([2], $I->grabDataFromResponseByJsonPath('meta.request_count'));
    }

    public function listSingleTest(FunctionalTester $I): void
    {
        $I->wantTo('see a message');

        $userId = $I->logIn();

        $I->haveInCollection('message', [
            'userId' => $userId,
            'content' => 'Whale hello!',
            'createdAt' => ['date' => '2019-01-28 01:23:45.678'],
        ]);

        $I->amOnPage('/api/messages');
        $I->assertSame([1], $I->grabDataFromResponseByJsonPath('meta.request_count'));
        $I->assertSame([], $I->grabDataFromResponseByJsonPath('meta.next'));
        $I->assertCount(1, $I->grabDataFromResponseByJsonPath('data'));
        $I->assertSame(
            ['Whale hello!'],
            $I->grabDataFromResponseByJsonPath('data[0].attributes.content')
        );
    }

    public function listManyTest(FunctionalTester $I): void
    {
        $I->wantTo('see a message list');

        $userId = $I->logIn();

        for ($i = 0; $i < 11; ++$i) {
            $I->haveInCollection('message', [
                'userId' => $userId,
                'content' => (99 - $i).' little whales sit on a tree',
                'createdAt' => ['date' => '2019-01-'.(28 - $i).' 01:23:45.678'],
            ]);
        }

        $I->amOnPage('/api/messages');
        $I->assertSame([1], $I->grabDataFromResponseByJsonPath('meta.request_count'));
        $I->assertSame(
            ['/api/messages?page=2'],
            $I->grabDataFromResponseByJsonPath('meta.next')
        );
        $I->assertSame([], $I->grabDataFromResponseByJsonPath('meta.prev'));
        $I->assertCount(10, $I->grabDataFromResponseByJsonPath('data[*]'));
        $I->assertSame(
            ['99 little whales sit on a tree'],
            $I->grabDataFromResponseByJsonPath('data[0].attributes.content')
        );

        $I->amOnPage('/api/messages?page=2');
        $I->assertSame([2], $I->grabDataFromResponseByJsonPath('meta.request_count'));
        $I->assertSame([], $I->grabDataFromResponseByJsonPath('meta.next'));
        $I->assertSame(
            ['/api/messages?page=1'],
            $I->grabDataFromResponseByJsonPath('meta.prev')
        );
        $I->assertCount(1, $I->grabDataFromResponseByJsonPath('data[*]'));
        $I->assertSame(
            ['89 little whales sit on a tree'],
            $I->grabDataFromResponseByJsonPath('data[0].attributes.content')
        );
    }
}
