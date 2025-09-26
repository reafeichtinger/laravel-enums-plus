<?php

namespace Rea\LaravelEnumsPlus;

use const JSON_ERROR_NONE;

use Countable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Validation\Rules\Enum as EnumValidationRule;

use function get_class;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

/**
 * @implements \Rea\LaravelEnumsPlus\EnumPlus<string,string>
 *
 * @mixin \EnumPlus<string,string>
 */
trait IsEnumPlus
{
    protected static function ensureImplementsInterface(): void
    {
        throw_unless(class_implements(static::class, EnumPlus::class), new \Exception(sprintf('Enum %s must implement EnumPlus', static::class)));
    }

    public static function options(): array
    {
        static::ensureImplementsInterface();

        return array_map(fn ($enum) => $enum->toArray(), static::cases());
    }

    public static function names(): array
    {
        static::ensureImplementsInterface();

        return array_map(fn ($enum) => $enum->name, static::cases());
    }

    public static function values(): array
    {
        static::ensureImplementsInterface();

        return array_map(fn ($enum) => $enum->value, static::cases());
    }

    public static function map(): array
    {
        static::ensureImplementsInterface();
        $array = [];

        foreach (static::cases() as $enum) {
            $array[$enum->value] = $enum->label();
        }

        return $array;
    }

    public static function labels(Countable|int|float|array $number = 1, array $replace = []): array
    {
        static::ensureImplementsInterface();

        return array_map(fn ($enum) => static::labelFor(value: $enum, number: $number, replace: $replace), static::cases());
    }

    public static function labelFor(self $value, Countable|int|float|array $number = 1, array $replace = []): string
    {
        static::ensureImplementsInterface();

        // If translations are defined in the enum
        if (method_exists($value, 'translations')) {
            return app('translator')->choice($value->translations()[app()->getLocale()][$value->value] ?? $value->value, $number, $replace);
        }

        // Fallback to translation keys
        $langKey = sprintf(
            '%s.%s.%s',
            'enums',
            static::class,
            $value->value
        );

        return app('translator')->has($langKey) ? trans_choice($langKey, $number, $replace) : $value->value;
    }

    public static function rule(): EnumValidationRule
    {
        static::ensureImplementsInterface();

        return new EnumValidationRule(static::class);
    }

    public function label(Countable|int|float|array $number = 1, array $replace = []): string
    {
        static::ensureImplementsInterface();

        return static::labelFor(value: $this, number: $number, replace: $replace);
    }

    public function withMeta(): array
    {
        static::ensureImplementsInterface();

        return [];
    }

    public function toArray(): array
    {
        static::ensureImplementsInterface();

        return [
            'name' => $this->name,
            'value' => $this->value,
            'label' => $this->label(),
            'meta' => $this->withMeta(),
        ];
    }

    public function toHtml(): string
    {
        static::ensureImplementsInterface();

        return $this->label();
    }

    public function toJson($options = 0): string
    {
        static::ensureImplementsInterface();
        $json = json_encode($this->toArray(), $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodingException('Error encoding enum [' . get_class($this) . '] with value [' . $this->value . '] to JSON: ' . json_last_error_msg());
        }

        return $json;
    }

    public function is(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return $this->isAny([$value]);
    }

    public function isA(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return $this->is($value);
    }

    public function isAn(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return $this->is($value);
    }

    public function isAny(array $values): bool
    {
        static::ensureImplementsInterface();

        if (empty($values)) {
            return false;
        }

        $values = array_map(fn ($value) => $value instanceof static ? $value : static::from($value), $values);

        return in_array($this, $values);
    }

    public function isNot(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return !$this->isAny([$value]);
    }

    public function isNotA(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return $this->isNot($value);
    }

    public function isNotAn(string|self $value): bool
    {
        static::ensureImplementsInterface();

        return $this->isNot($value);
    }

    public function isNotAny(array $values): bool
    {
        static::ensureImplementsInterface();

        return !$this->isAny($values);
    }
}
