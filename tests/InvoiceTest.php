<?php

namespace TwentyTwoDigital\CashierFastspring\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;
use TwentyTwoDigital\CashierFastspring\Tests\TestCase;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Database;
use TwentyTwoDigital\CashierFastspring\Tests\Traits\Model;

class InvoiceTest extends TestCase
{
    use Database;
    use Model;

    /**
     * Tests.
     */
    public function testOrder()
    {
        $email = 'johndoe@test.com';

        $user = $this->createUser(['email' => $email, 'fastspring_id' => 'fastspring_id']);

        $invoice = $user->invoices()->create([
            'fastspring_id'                  => 'fastspring_id',
            'type'                           => 'subscription',
            'subscription_display'           => 'subscription_display',
            'subscription_product'           => 'subscription_product',
            'subscription_sequence'          => 'subscription_sequence',
            'invoice_url'                    => 'invoice_url',
            'total'                          => 0,
            'tax'                            => 0,
            'subtotal'                       => 0,
            'discount'                       => 0,
            'currency'                       => 'USD',
            'payment_type'                   => 'test',
            'completed'                      => true,
            'created_at'                     => date('Y-m-d H:i:s'),
            'updated_at'                     => date('Y-m-d H:i:s'),
            'subscription_period_start_date' => date('Y-m-d H:i:s'),
            'subscription_period_end_date'   => date('Y-m-d H:i:s'),
        ]);

        $this->assertInstanceOf('Carbon\Carbon', $invoice->created_at);
        $this->assertInstanceOf('Carbon\Carbon', $invoice->updated_at);
        $this->assertInstanceOf('Carbon\Carbon', $invoice->subscription_period_start_date);
        $this->assertInstanceOf('Carbon\Carbon', $invoice->subscription_period_end_date);
        $this->assertEquals($invoice->user->email, $email);
    }
}
