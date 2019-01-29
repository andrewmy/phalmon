<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Utility;
use App\Tests\UnitTestCase;
use Phalcon\Http\Response;

class UtilityTest extends UnitTestCase
{
    public function testReturnsError(): void
    {
        $response = new Response();
        Utility::returnError($response, 401, 'Bad creds');

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame(
            ['errors' => [['code' => '401', 'title' => 'Bad creds']]],
            \json_decode($response->getContent(), true)
        );

        $response = new Response();
        Utility::returnError($response, 401, 'Bad creds', 'Really');

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame(
            ['errors' => [
                ['code' => '401', 'title' => 'Bad creds', 'detail' => 'Really'],
            ]],
            \json_decode($response->getContent(), true)
        );
    }

    public function testReturnsNoContent(): void
    {
        $response = new Response();
        Utility::returnNoContent($response, 418, "I'm a teapot");

        $this->assertSame(418, $response->getStatusCode());
        $this->assertSame(
            [],
            \json_decode($response->getContent(), true)
        );
    }

    public function formatsMongoDate(): void
    {
        $result = Utility::mongoDateFormat([], 'd.m.Y');
        $this->assertNull($result);

        $result = Utility::mongoDateFormat(
            ['date' => '2019-01-28 01:23:45.678'], 'd.m.Y'
        );
        $this->assertSame('28.01.2019', $result);
    }
}
