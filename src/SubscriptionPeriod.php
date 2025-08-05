<?php
/**
 * This file implements Subscription Period.
 *
 * @author    Bilal Gultekin <bilal@gultekin.me>
 * @author    Justin Hartman <justin@22digital.co.za>
 * @copyright 2019 22 Digital
 * @license   MIT
 * @since     v0.1
 */

namespace TwentyTwoDigital\CashierFastspring;

use Illuminate\Database\Eloquent\Model;

/**
 * This class describes a subscription period.
 *
 * {@inheritdoc}
 */
class SubscriptionPeriod extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription.
     *
     * @return object Subscription object.
     */
    public function subscription()
    {
        return $this->belongsTo('TwentyTwoDigital\CashierFastspring\Subscription');
    }
}
