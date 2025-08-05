<?php
/**
 * This file implements Invoice.
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
 * This class describes an invoice.
 *
 * {@inheritdoc}
 */
class Invoice extends Model
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'subscription_period_start_date' => 'date',
        'subscription_period_end_date' => 'date',
    ];

    /**
     * Get the user that owns the invoice.
     *
     * @return self
     */
    public function user()
    {
        return $this->owner();
    }

    /**
     * Get the model related to the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        $model = getenv('FASTSPRING_MODEL') ?: config('services.fastspring.model', 'App\\User');

        $model = new $model();

        return $this->belongsTo(get_class($model), $model->getForeignKey());
    }
}
