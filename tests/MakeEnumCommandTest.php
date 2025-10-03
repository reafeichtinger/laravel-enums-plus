<?php

use Illuminate\Foundation\Console\EnumMakeCommand;
use Illuminate\Support\Facades\File;

use function Orchestra\Testbench\workbench_path;
use function Pest\Laravel\artisan;

it('can create an enum', function () {

    File::delete(workbench_path('app/Enums/TestEnum.php'));

    artisan('make:enum-plus TestEnum -s')
        ->execute();

    expect(workbench_path('app/Enums/TestEnum.php'))->toBeFile();
})
    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    ->skip(!class_exists(EnumMakeCommand::class), 'Laravel 11 only');

it('can make pure enum', function () {

    File::delete(workbench_path('app/Enums/PureEnum.php'));

    artisan('make:enum-plus PureEnum')
        ->execute();

    expect(workbench_path('app/Enums/PureEnum.php'))->toBeFile();

    $expectedContents = File::get(__DIR__ . '/Fixtures/ExpectedPureEnum.php');
    $actualContents = File::get(workbench_path('app/Enums/PureEnum.php'));

    expect($actualContents)->toEqual($expectedContents);
})
    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    ->skip(!class_exists(EnumMakeCommand::class), 'Laravel 11 only');

it('can make string enum', function () {

    File::delete(workbench_path('app/Enums/StringEnum.php'));

    artisan('make:enum-plus StringEnum --string')
        ->execute();

    expect(workbench_path('app/Enums/StringEnum.php'))->toBeFile();

    $expectedContents = File::get(__DIR__ . '/Fixtures/ExpectedStringEnum.php');
    $actualContents = File::get(workbench_path('app/Enums/StringEnum.php'));

    expect($actualContents)->toEqual($expectedContents);
})
    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    ->skip(!class_exists(EnumMakeCommand::class), 'Laravel 11 only');

it('can make int enum', function () {

    File::delete(workbench_path('app/Enums/IntEnum.php'));

    artisan('make:enum-plus IntEnum --int')
        ->execute();

    expect(workbench_path('app/Enums/IntEnum.php'))->toBeFile();

    $expectedContents = File::get(__DIR__ . '/Fixtures/ExpectedIntEnum.php');
    $actualContents = File::get(workbench_path('app/Enums/IntEnum.php'));

    expect($actualContents)->toEqual($expectedContents);
})
    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    ->skip(!class_exists(EnumMakeCommand::class), 'Laravel 11 only');
