<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Factory\UserFactory;
use App\Tests\UnitTestCase;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Security;

class UserFactoryTest extends UnitTestCase
{
    public function testHashesPassword(): void
    {
        $collectionManager = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->di->setShared('collectionManager', $collectionManager);

        $service = new UserFactory(new Security());

        $user = $service->create('phteven', 'phan');

        $this->assertSame('phteven', $user->username);
        $this->assertStringStartsWith('$2y$13$', $user->password);
    }
}
