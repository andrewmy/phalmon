<?php

declare(strict_types=1);

namespace App\Tests\Unit\Exception;

use App\Exceptions\Unauthorized;
use App\Tests\UnitTestCase;

class UnauthorizedTest extends UnitTestCase
{
    public function testDefaultCode(): void
    {
        $exception = new Unauthorized();

        $this->assertSame(401, $exception->getCode());
    }
}
