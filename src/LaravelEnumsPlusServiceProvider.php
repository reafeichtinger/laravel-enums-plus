<?php

namespace Rea\LaravelEnumsPlus;

use Illuminate\Foundation\Console\EnumMakeCommand;
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
            ->hasConfigFile();

        /** @noinspection PhpFullyQualifiedNameUsageInspection */
        if (class_exists(EnumMakeCommand::class)) {
            $package->hasConsoleCommand(LaravelEnumsPlusMakeCommand::class);
        }
    }
}
