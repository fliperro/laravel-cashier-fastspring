<?php

namespace TwentyTwoDigital\CashierFastspring\Tests\Traits;

use Workbench\App\Models\User;

trait Model
{
    public function createUser($parameters = [])
    {
        return User::create(array_merge([
            'email' => 'bilal@gultekin.me',
            'name'  => 'Bilal Gultekin',
            'password' => '$2a$12$.6qft33O/v18IoTQXjkKAeSZmBiOQCGO.I21LyhZV6GjFpjh1/Gum',
        ], $parameters));
    }

    public function createSubscription($user, $parameters = [])
    {
        return $user->subscriptions()->create(array_merge([
            'name'            => 'main',
            'fastspring_id'   => 'fastspring_id',
            'plan'            => 'starter-plan',
            'state'           => 'active',
            'quantity'        => 1,
            'currency'        => 'USD',
            'interval_unit'   => 'month',
            'interval_length' => 1,
        ], $parameters));
    }

    public function createSubscriptionPeriod($subscription, $parameters = [])
    {
        return $subscription->periods()->create(array_merge([
            'type'       => 'local',
            'start_date' => '2010-01-01',
            'end_date'   => '2010-02-01',
        ], $parameters));
    }
}
