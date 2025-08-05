<?php

namespace TwentyTwoDigital\CashierFastspring\Tests;

use TwentyTwoDigital\CashierFastspring\Tests\TestCase;
use TwentyTwoDigital\CashierFastspring\Exceptions\NotImplementedException;

class ExceptionsTest extends TestCase
{
    public function testNotImplementedExceptionCanBeConstructed()
    {
        $this->assertInstanceOf(NotImplementedException::class, new NotImplementedException());
    }
}
