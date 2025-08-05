<?php

namespace TwentyTwoDigital\CashierFastspring\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends OrchestraTestCase
{
    use WithWorkbench;
}
