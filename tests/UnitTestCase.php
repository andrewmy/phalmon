<?php

declare(strict_types=1);

namespace App\Tests;

use Phalcon\Di;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    /** @var bool */
    private $_loaded = false;

    public function setUp(): void
    {
        parent::setUp();

        // Load any additional services that might be required during testing
        $container = Di::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent

        $this->setDi($container);

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly.
     *
     * @throws \PHPUnit\Framework\IncompleteTestError
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit\Framework\IncompleteTestError(
                'Please run parent::setUp().'
            );
        }
    }
}
