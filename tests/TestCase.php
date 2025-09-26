<?php

namespace Rea\LaravelEnumsPlus\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Rea\LaravelEnumsPlus\LaravelEnumsPlusServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelEnumsPlusServiceProvider::class,
        ];
    }

    public static function applicationBasePath(): string
    {
        return __DIR__ . '/../workbench';
    }
}
