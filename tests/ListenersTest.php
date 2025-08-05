<?php

namespace TwentyTwoDigital\CashierFastspring\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;
use TwentyTwoDigital\CashierFastspring\Tests\TestCase;
use TwentyTwoDigital\CashierFastspring\Events;
use TwentyTwoDigital\CashierFastspring\Fastspring\Fastspring;
use TwentyTwoDigital\CashierFastspring\Invoice;
use TwentyTwoDigital\CashierFastspring\Listeners;
use TwentyTwoDigital\CashierFastspring\Subscription;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Database;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Model;

class ListenersTest extends TestCase
{
    use Database;
    use Model;

    /**
     * Tests.
     */
    public function testOrderCompleteListener()
    {
        $user = $this->createUser(['fastspring_id' => 'fastspring_id']);
        // retrieved from fastspring's doc
        $data = $this->payloadOfOrderCompleted('fastspring_id');

        $event = new Events\OrderCompleted('id', 'order.completed', true, true, time(), $data);
        $listener = new Listeners\OrderCompleted();
        $listener->handle($event);

        $invoice = Invoice::where('fastspring_id', $data['id'])->first();
        $this->assertNotNull($invoice);
    }

    public function testSubscriptionActivatedListener()
    {
        $user = $this->createUser(['fastspring_id' => 'fastspring_id']);
        // retrieved from fastspring's doc
        $data = $this->payloadOfSubscriptionActivated('fastspring_id');

        $event = new Events\SubscriptionActivated('id', 'subscription.activated', true, true, time(), $data);
        $listener = new Listeners\SubscriptionActivated();
        $listener->handle($event);

        $subscription = Subscription::where('fastspring_id', $data['id'])->first();

        $this->assertNotNull($subscription);
        $this->assertEquals($subscription->periods->count(), 1);
    }

    public function testSubscriptionChargeCompletedListener()
    {
        $user = $this->createUser(['fastspring_id' => 'fastspring_id']);
        $subscription = $this->createSubscription($user, ['state' => 'overdue', 'fastspring_id' => 'subscription_id']);

        // retrieved from fastspring's doc
        $data = $this->payloadOfSubscriptionChargeCompleted('subscription_id', 'fastspring_id');

        $event = new Events\SubscriptionChargeCompleted(
            'id',
            'subscription.charge.completed',
            true,
            true,
            time(),
            $data
        );
        $listener = new Listeners\SubscriptionChargeCompleted();
        $listener->handle($event);

        $invoice = Invoice::where('fastspring_id', $data['order']['id'])->first();
        $this->assertNotNull($invoice);
    }

    public function testSubscriptionStateChangeListener()
    {
        $user = $this->createUser(['fastspring_id' => 'fastspring_account_id']);
        $subscription = $this->createSubscription($user, ['fastspring_id' => 'fastspring_subscription_id']);

        // retrieved from fastspring's doc
        $data = $this->payloadOfSubscriptionCanceled('fastspring_subscription_id', 'fastspring_account_id');

        $event = new Events\SubscriptionCanceled('id', 'subscription.canceled', true, true, time(), $data);
        $listener = new Listeners\SubscriptionStateChanged();
        $listener->handle($event);

        $subscription = Subscription::where('fastspring_id', $data['id'])->first();
        $this->assertNotNull($subscription);
        $this->assertEquals($subscription->state, $data['state']);
    }

    public function testSubscriptionDeactivatedListener()
    {
        $user = $this->createUser(['fastspring_id' => 'fastspring_id']);
        $subscription = $this->createSubscription($user, ['fastspring_id' => 'fastspring_subscription_id']);

        // retrieved from fastspring's doc
        $data = $this->payloadOfSubscriptionDeactivated('fastspring_id', 'fastspring_subscription_id');

        $event = new Events\SubscriptionDeactivated('id', 'subscription.activated', true, true, time(), $data);
        $listener = new Listeners\SubscriptionDeactivated();
        $listener->handle($event);

        $subscription = Subscription::where('fastspring_id', $data['id'])->first();

        $this->assertEquals($subscription->state, 'deactivated');
    }

    /**
     * Payload from fastspring.
     */
    protected function payloadOfSubscriptionCanceled($subscriptionId, $accountId)
    {
        $template = file_get_contents(__DIR__ . '/Payloads/subscription_canceled.json');
        $jsonString = $this->renderTemplate(
            $template,
            ['subscriptionId' => $subscriptionId, 'accountId' => $accountId]
        );

        return json_decode($jsonString, true);
    }

    protected function payloadOfSubscriptionActivated($accountId)
    {
        $template = file_get_contents(__DIR__ . '/Payloads/subscription_activated.json');
        $jsonString = $this->renderTemplate($template, ['accountId' => $accountId]);

        return json_decode($jsonString, true);
    }

    protected function payloadOfSubscriptionDeactivated($accountId, $subscriptionId)
    {
        $template = file_get_contents(__DIR__ . '/Payloads/subscription_deactivated.json');
        $jsonString = $this->renderTemplate($template, [
            'accountId'      => $accountId,
            'subscriptionId' => $subscriptionId,
        ]);

        return json_decode($jsonString, true);
    }

    protected function payloadOfSubscriptionChargeCompleted($subscriptionId, $accountId)
    {
        $template = file_get_contents(__DIR__ . '/Payloads/subscription_charge_completed.json');
        $jsonString = $this->renderTemplate(
            $template,
            ['subscriptionId' => $subscriptionId, 'accountId' => $accountId]
        );

        return json_decode($jsonString, true);
    }

    protected function payloadOfOrderCompleted($accountId)
    {
        $template = file_get_contents(__DIR__ . '/Payloads/order_completed.json');
        $jsonString = $this->renderTemplate($template, ['accountId' => $accountId]);

        return json_decode($jsonString, true);
    }

    protected function renderTemplate($template, $data)
    {
        $dataKeys = array_keys($data);
        $replacements = array_values($data);

        $patterns = [];

        foreach ($dataKeys as $dataKey) {
            $patterns[] = '/{' . $dataKey . '}/';
        }

        return preg_replace($patterns, $replacements, $template);
    }
}
