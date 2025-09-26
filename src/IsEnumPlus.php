<?php

namespace Rea\LaravelEnumsPlus;

use const JSON_ERROR_NONE;

use Countable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum as EnumValidationRule;
use RuntimeException;

use function get_class;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

/**
 * @implements Rea\LaravelEnumsPlus\EnumPlus<string,string>
 *
 * @mixin EnumPlus<string,string>
 */
trait IsEnumPlus
{
    #region static

    /**
     * Make sure the current enum implements the EnumPlus interface so all automatic casting works properly.
     */
    protected static function ensureImplementsInterface(): void
    {
        throw_unless(is_subclass_of(static::class, EnumPlus::class),
            new RuntimeException(sprintf('Enum %s must implement IsEnumPlus interface.', static::class))
        );
    }

    /**
     * Collect all matching enum cases. Optionally certain items can be excluded.
     *
     * @param  null|self|string|array<self|string>|Collection<self|string>  $exclude  The enum case(s) to exclude.
     * @return Collection<self> A collection of the matching enum cases.
     */
    public static function casesC(null|string|self|array|Collection $exclude = null): Collection
    {
        static::ensureImplementsInterface();

        return Collection::make(static::cases())->when($exclude, function ($cases) use ($exclude) {
            return $cases->filter(fn ($enum) => is_array($exclude) ? $enum->isNotAny($exclude) : $enum->isNot($exclude))->values();
        });
    }

    /**
     * Get all matching enum cases as a Collection with full data, neatly wrapped in a Collection.
     */
    public static function optionsC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->map(fn ($enum) => $enum->toCollection());
    }

    /**
     * Get all matching enum cases as an array with full data.
     */
    public static function options(null|string|self|array|Collection $exclude = null): array
    {
        return static::optionsC($exclude)->toArray();
    }

    /**
     * Get all matching case names (uppercase), neatly wrapped in a Collection.
     */
    public static function namesC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->map(fn ($enum) => $enum->name);
    }

    /**
     * Get all matching case names (uppercase).
     */
    public static function names(null|string|self|array|Collection $exclude = null): array
    {
        return static::casesC($exclude)->map(fn ($enum) => $enum->name)->toArray();
    }

    /**
     * Get all matching case values, neatly wrapped in a Collection.
     */
    public static function valuesC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->map(fn ($enum) => $enum->value);
    }

    /**
     * Get all matching case values.
     */
    public static function values(null|string|self|array|Collection $exclude = null): array
    {
        return static::valuesC($exclude)->toArray();
    }

    /**
     * Get all matching cases as a dictonary with the value as the key and the translation as the value, neatly wrapped in a Collection.
     */
    public static function dictC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->mapWithKeys(function ($enum) {
            return [$enum->value => $enum->label()];
        });
    }

    /**
     * Get all matching cases as a dictonary with the value as the key and the translation as the value.
     */
    public static function dict(null|string|self|array|Collection $exclude = null): array
    {
        return static::casesC($exclude)->mapWithKeys(function ($enum) {
            return [$enum->value => $enum->label()];
        })->toArray();
    }

    /**
     * Get all matching translations, neatly wrapped in a Collection.
     */
    public static function labelsC(null|string|self|array|Collection $exclude = null, Countable|int|float|array $number = 1, array $replace = []): Collection
    {
        return static::casesC($exclude)->map(fn ($enum) => static::labelFor(value: $enum, number: $number, replace: $replace));
    }

    /**
     * Get all matching translations.
     */
    public static function labels(null|string|self|array|Collection $exclude = null, Countable|int|float|array $number = 1, array $replace = []): array
    {
        return static::casesC($exclude)->map(fn ($enum) => static::labelFor(value: $enum, number: $number, replace: $replace))->toArray();
    }

    /**
     * Get the translation for the given enum case. Supports pluralizations and placeholders.
     */
    public static function labelFor(self $value, Countable|int|float|array $number = 1, array $replace = []): string
    {
        static::ensureImplementsInterface();

        // If translations are defined in the enum and the requested key exists for the current locale
        if (method_exists($value, 'translations') && array_key_exists($value->value, $value->translations()[app()->getLocale()] ?? [])) {
            return app('translator')->choice(
                $value->translations()[app()->getLocale()][$value->value] ??
                $value->value, $number, $replace
            );
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

    /**
     * Get the validation rules for the matching cases.
     */
    public static function rule(null|string|self|array|Collection $exclude = null): EnumValidationRule
    {
        static::ensureImplementsInterface();

        return new EnumValidationRule(static::class)->only(static::values($exclude));
    }

    #endregion static
    #region instance

    /**
     * Get the translation for the current enum case. Supports pluralizations and placeholders.
     */
    public function label(Countable|int|float|array $number = 1, array $replace = []): string
    {
        return static::labelFor(value: $this, number: $number, replace: $replace);
    }

    /**
     * Definition of what metadata should be included, empty array by default.
     */
    public function withMeta(): array
    {
        static::ensureImplementsInterface();

        return [];
    }

    /**
     * The current enum as an array, including key, value, label and meta data.
     */
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

    /**
     * The current enum as a collection, including key, value, label and meta data.
     */
    public function toCollection(): Collection
    {
        return Collection::make($this->toArray());
    }

    /**
     * The current enum as a html string consisting of its label.
     */
    public function toHtml(): string
    {
        return $this->label();
    }

    /**
     * The current enum as a json string of its array representation, including key, value, label and meta data.
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodingException('Error encoding enum [' . get_class($this) . '] with value [' . $this->value . '] to JSON: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Check if the current enum instance is the same as $value.
     */
    public function is(string|self $value): bool
    {
        return $this->isAny([$value]);
    }

    /**
     * Check if the current enum instance is the same as $value. Alias for `is()`.
     */
    public function isA(string|self $value): bool
    {
        return $this->is($value);
    }

    /**
     * Check if the current enum instance is the same as $value. Alias for `is()`.
     */
    public function isAn(string|self $value): bool
    {
        return $this->is($value);
    }

    /**
     * Check if the current enum instance is one of the provided $values.
     */
    public function isAny(array $values): bool
    {
        static::ensureImplementsInterface();

        if (empty($values)) {
            return false;
        }

        $values = array_map(fn ($value) => $value instanceof static ? $value : static::tryFrom(strtolower($value)), $values);

        return in_array($this, $values);
    }

    /**
     * Check if the current enum instance is NOT the same as $value.
     */
    public function isNot(string|self $value): bool
    {
        return !$this->isAny([$value]);
    }

    /**
     * Check if the current enum instance is NOT the same as $value. Alias for `isNot()`.
     */
    public function isNotA(string|self $value): bool
    {
        return $this->isNot($value);
    }

    /**
     * Check if the current enum instance is NOT the same as $value. Alias for `isNot()`.
     */
    public function isNotAn(string|self $value): bool
    {
        return $this->isNot($value);
    }

    /**
     * Check if the current enum instance is NOT one of the provided $values.
     */
    public function isNotAny(array $values): bool
    {
        return !$this->isAny($values);
    }

    #endregion instance
}
