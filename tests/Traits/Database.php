<?php

namespace TwentyTwoDigital\CashierFastspring\Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;

#[WithMigration]
trait Database
{
    use RefreshDatabase;
}
