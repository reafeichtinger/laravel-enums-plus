<?php

namespace Rea\LaravelEnumsPlus\Commands;

use Illuminate\Foundation\Console\EnumMakeCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:enum-plus')]
class MakeEnumPlusCommand extends EnumMakeCommand
{
    protected $name = 'make:enum-plus';
    protected $description = 'Create a new supercharged laravel enum';

    protected function getStub(): string
    {
        if ($this->option('string') || $this->option('int')) {
            return $this->resolveStubPath('/stubs/laravel-enums-plus.stub');
        }

        return parent::getStub();
    }

    protected function buildClass($name): array|string
    {
        if ($this->option('string') || $this->option('int')) {
            return str_replace(
                ['{{ value }}'],
                $this->option('string') ? '\'standard\'' : '0',
                parent::buildClass($name)
            );
        }

        return parent::buildClass($name);
    }

    protected function resolveStubPath($stub): string
    {

        if (file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))) {
            return $customPath;
        }

        if (file_exists(__DIR__ . '/../../' . $stub)) {
            return __DIR__ . '/../../' . $stub;
        }

        return parent::resolveStubPath($stub);
    }

    protected function getNameInput(): string
    {
        $name = trim($this->argument('name'));
        if (!preg_match('/^[A-Za-z_\x7f-\xff][A-Za-z0-9_\x7f-\xff]*$/', $name)) {
            throw new InvalidArgumentException('Invalid enum name format');
        }

        if (str_ends_with($name, 'Enum')) {
            return $name;
        }

        return $name . 'Enum';
    }
}
