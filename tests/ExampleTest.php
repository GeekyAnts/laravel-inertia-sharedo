<?php

namespace Geekyants\Sharedo\Tests;

use Orchestra\Testbench\TestCase;
use Geekyants\Sharedo\SharedoServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [SharedoServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
