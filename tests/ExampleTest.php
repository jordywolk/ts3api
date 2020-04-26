<?php

namespace Jordywolk\Ts3api\Tests;

use Orchestra\Testbench\TestCase;
use Jordywolk\Ts3api\Ts3apiServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [Ts3apiServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
