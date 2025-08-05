<?php

namespace TwentyTwoDigital\CashierFastspring\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;
use TwentyTwoDigital\CashierFastspring\Tests\TestCase;
use TwentyTwoDigital\CashierFastspring\SubscriptionPeriod;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Database;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Model;

class SubscriptionPeriodTest extends TestCase
{
    use Database;
    use Model;

    /**
     * Tests.
     */
    public function testSubscriptionPeriodCanBeConstructed()
    {
        $this->assertInstanceOf(SubscriptionPeriod::class, new SubscriptionPeriod());
    }

    public function testSubscriptionPeriodCanBeInserted()
    {
        $email = 'bilal@gultekin.me';

        $user = $this->createUser(['email' => $email, 'fastspring_id' => 'fastspring_id']);
        $subscription = $this->createSubscription($user, ['state' => 'active']);
        $period = $this->createSubscriptionPeriod($subscription);

        $this->assertEquals($period->subscription->id, $subscription->id);
    }
}
