<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use TwentyTwoDigital\CashierFastspring\Billable;

class User extends Authenticatable
{
    use HasFactory;
    use Billable;

    protected $guarded = [];
}
