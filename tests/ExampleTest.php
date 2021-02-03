<?php

namespace Geekyants\ShareDialog\Tests;

use Orchestra\Testbench\TestCase;
use Geekyants\ShareDialog\ShareDialogServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ShareDialogServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
