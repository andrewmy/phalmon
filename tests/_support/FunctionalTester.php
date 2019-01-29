<?php

declare(strict_types=1);

/**
 * Inherited Methods.
 *
 * @method void                    wantToTest($text)
 * @method void                    wantTo($text)
 * @method void                    execute($callable)
 * @method void                    expectTo($prediction)
 * @method void                    expect($prediction)
 * @method void                    amGoingTo($argumentation)
 * @method void                    am($role)
 * @method void                    lookForwardTo($achieveValue)
 * @method void                    comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    public function logIn(
        string $username = 'phteven', string $password = 'phan'
    ): string {
        $this->haveInCollection('user', [
            'username' => 'han'.\mt_rand(),
            'password' => $this->getApplication()->getDI()->get('security')->hash('solo'),
        ]);

        $userId = $this->haveInCollection('user', [
            'username' => $username,
            'password' => $this->getApplication()->getDI()->get('security')
                ->hash($password),
        ]);

        $this->haveHttpHeader('Content-type', 'application/json');
        $this->sendPOST(
            '/api/login', ['username' => $username, 'password' => $password]
        );
        $this->seeResponseCodeIs(200);
        $this->see('token');
        $token = $this->grabDataFromResponseByJsonPath('token');
        $this->amBearerAuthenticated($token[0]);

        return $userId;
    }
}
