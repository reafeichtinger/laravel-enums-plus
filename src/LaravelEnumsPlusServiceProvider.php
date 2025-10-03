<?php

namespace Rea\LaravelEnumsPlus;

use Rea\LaravelEnumsPlus\Commands\MakeEnumPlusCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEnumsPlusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-enums-plus')
            ->hasConfigFile('enums-plus')
            ->hasCommand(MakeEnumPlusCommand::class);
    }
}
