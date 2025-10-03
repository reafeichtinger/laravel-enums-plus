<?php

namespace Rea\LaravelEnumsPlus;

use const JSON_ERROR_NONE;

use Closure;
use Countable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

        return Collection::make(static::cases())->when($exclude, function (Collection $cases) use ($exclude) {
            return $cases->filter(fn (self $enum) => is_array($exclude) ? $enum->isNotAny($exclude) : $enum->isNot($exclude))->values();
        });
    }

    /**
     * Get all matching enum cases as a Collection with full data, neatly wrapped in a Collection.
     */
    public static function optionsC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->map(fn (self $enum) => $enum->toCollection());
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
        return static::casesC($exclude)->map(fn (self $enum) => $enum->name);
    }

    /**
     * Get all matching case names (uppercase).
     */
    public static function names(null|string|self|array|Collection $exclude = null): array
    {
        return static::namesC($exclude)->toArray();
    }

    /**
     * Get all matching case values, neatly wrapped in a Collection.
     */
    public static function valuesC(null|string|self|array|Collection $exclude = null): Collection
    {
        return static::casesC($exclude)->map(fn (self $enum) => $enum->value);
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
        return static::casesC($exclude)->mapWithKeys(function (self $enum) {
            return [$enum->value => $enum->label()];
        });
    }

    /**
     * Get all matching cases as a dictonary with the value as the key and the translation as the value.
     */
    public static function dict(null|string|self|array|Collection $exclude = null): array
    {
        return static::dictC($exclude)->toArray();
    }

    /**
     * Get all matching translations, neatly wrapped in a Collection.
     */
    public static function labelsC(null|string|self|array|Collection $exclude = null, null|Countable|int|float|array $number = null, ?array $replace = null): Collection
    {
        return static::casesC($exclude)->map(fn (self $enum) => static::labelFor(
            value: $enum,
            number: $number,
            replace: $replace,
        ));
    }

    /**
     * Get all matching translations.
     */
    public static function labels(null|string|self|array|Collection $exclude = null, null|Countable|int|float|array $number = null, ?array $replace = null): array
    {
        return static::labelsC($exclude, $number, $replace)->toArray();
    }

    /**
     * Get the translation for the given enum case. Supports pluralizations and placeholders.
     */
    public static function labelFor(self $value, null|Countable|int|float|array $number = null, ?array $replace = null): string
    {
        static::ensureImplementsInterface();

        $number = $number ?? $value->defaultCount() ?? config('enums-plus.default_count');
        $replace = $replace ?? $value->defaultReplace() ?? config('enums-plus.default_replace');
        // dd($number, $replace);
        // If translations are defined in the enum and the requested key exists for the current locale
        if (method_exists($value, 'translations') && array_key_exists($value->value, $value->translations()[app()->getLocale()] ?? [])) {
            return app('translator')->choice(
                $value->translations()[app()->getLocale()][$value->value] ?? $value->value,
                $number,
                $replace,
            );
        }

        // Fallback to translation keys
        $langKey = sprintf(
            '%s.%s.%s',
            config('enums-plus.translations', 'enums'),
            static::class,
            $value->value
        );

        return app('translator')->has($langKey) ? trans_choice($langKey, $number, $replace) : $value->value;
    }

    /**
     * Get the translation for the given enum case. Supports pluralizations and placeholders.
     */
    public static function transFor(self $value, null|Countable|int|float|array $number = null, ?array $replace = null): string
    {
        return static::labelFor($value, $number, $replace);
    }

    /**
     * Get the validation rules for the matching cases.
     */
    public static function rule(null|string|self|array|Collection $exclude = null): EnumValidationRule
    {
        static::ensureImplementsInterface();

        return new EnumValidationRule(static::class)->only(static::values($exclude));
    }

    /**
     * Parse this enums values into a valid format for select components, neatly wrapped in a Collection.
     *
     * @param  null|string|self|array|Collection  $selected  The currently selected case(s).
     * @param  null|string|self|array|Collection  $exclude  What enum case(s) should not be included.
     * @param  null|string|Closure  $translation  When null the label() method is used. When a string, it uses the string value as a base translation path. When a closure, the result of the closure will be used.
     * @param  string[]|Closure[]  $columns  Any additional columns to add to the result. Strings can be method or property names on the enum, closures will be called and the return value is used.
     */
    public static function selectC(null|string|self|array|Collection $selected = null, null|string|self|array|Collection $exclude = null, null|string|Closure $translation = null, string|Closure ...$columns): Collection
    {
        return static::casesC($exclude)->map(function (self $case) use ($selected, $translation, $columns) {

            // Build the actual label
            $l = $translation instanceof Closure ? $translation($case) : (is_string($translation) ? trans_choice(Str::finish(($translation), $translation == '' ? '' : '.') . $case->value, 1) : null);
            $l = $l ?? $case->label();

            // Determine if the case is selected
            $s = in_array($case->value, array_map(fn (self|string $case) => $case instanceof self ? $case->value : $case, Arr::wrap($selected)));

            // Prepare the result
            $result = [
                config('enums-plus.columns.value', 'value') => $case->value,
                config('enums-plus.columns.label', 'label') => $l,
                config('enums-plus.columns.selected', 'selected') => $s ? true : null,
            ];

            // Add additional columns if given
            foreach ($columns ?? [] as $key => $value) {
                // Call the closure if given
                if ($value instanceof Closure) {
                    $result[$key] = $value($case);
                } // Try calling method with the given name or get property value
                elseif (is_string($value)) {
                    $result[$key] = method_exists($case, $value) ? $case->{$value}() : ($case->$value ?? null);
                }
            }

            // Remove actual null values and empty strings
            return array_filter($result, fn (mixed $e) => $e !== null && (!is_string($e) || (is_string($e) && trim($e) !== '')));
        })->values();
    }

    /**
     * Parse this enums values into a valid format for select components.
     *
     * @param  null|string|self|array|Collection  $selected  The currently selected case(s).
     * @param  null|string|self|array|Collection  $exclude  What enum case(s) should not be included.
     * @param  null|string|Closure  $translation  When null the label() method is used. When a string, it uses the string value as a base translation path. When a closure, the result of the closure will be used.
     * @param  string[]|Closure[]  $columns  Any additional columns to add to the result. Strings can be method or property names on the enum, closures will be called and the return value is used.
     */
    public static function select(null|string|self|array|Collection $selected = null, null|string|self|array|Collection $exclude = null, null|string|Closure $translation = null, string|Closure ...$columns): array
    {
        return static::selectC($selected, $exclude, $translation, ...$columns)->toArray();
    }

    /**
     * Get all matching cases including the calculated Levenshtein distance to the given $input, neatly wrapped in a Collection.
     *
     * @param  string|self  $input  The string that should be matched.
     */
    public static function matchesC(string|self $input): Collection
    {
        static::ensureImplementsInterface();
        $inputNormalized = Str::slug(trim($input), '');

        if ($input instanceof self || ($input = static::tryFrom($inputNormalized))) {
            return Collection::make([Collection::make(['case' => $input->toArray(), 'distance' => 0])]);
        }

        // Find all cases with direct string matches
        $matches = static::casesC()->filter(function (self $case) use ($inputNormalized) {
            return Collection::make($case->withAlias())
                // Check all name, value, label AND all aliases of the case
                ->add($case->value)
                ->add($case->name)
                ->add($case->label())
                // Normalize aliases and value
                ->map(fn (string $alias) => Str::slug(trim($alias)))
                // Filter out duplicates
                ->unique()
                // Filter out everything that doesn't match our input
                ->filter(fn (string $alias) => Str::contains($alias, $inputNormalized) || Str::contains($inputNormalized, $alias))
                // If we have at lease one entry left, then this case matches the input
                ->count() > 0;
        });

        // In case we couldn't find any direct matches, use all cases for the distance calculation
        if ($matches->count() === 0) {
            $matches = static::casesC();
        }

        // Calculate levenshtein distance
        return $matches->map(function (self $case) use ($inputNormalized) {
            $caseNormalized = Str::slug(trim($case->value));
            $distance = levenshtein($inputNormalized, $caseNormalized);

            return Collection::make(['case' => $case->toArray(), 'distance' => $distance]);
        })->sortBy('distance')->values();
    }

    /**
     * Get all matching cases including the calculated Levenshtein distance to the given $input.
     *
     * @param  string|self  $input  The string that should be matched.
     */
    public static function matches(string|self $input): array
    {
        return static::matchesC($input)->toArray();
    }

    /**
     * Parse any string to the closest matching enum value.
     *
     * NOTE: This uses Levenshtein distance to calculate the closest match as a last resort which can produce strange results.
     *
     * @param  string|self  $input  The string that should be matched to an enum case.
     */
    public static function parse(string|self $input): static
    {
        return static::tryFrom(Arr::get(static::matchesC($input)->first(), 'case.value', default: self::casesC()->first()->value));
    }

    #endregion static
    #region instance

    /**
     * Get the translation for the current enum case. Supports pluralizations and placeholders.
     */
    public function label(null|Countable|int|float|array $number = null, ?array $replace = null): string
    {
        return static::labelFor(value: $this, number: $number, replace: $replace);
    }

    /**
     * Get the translation for the current enum case. Supports pluralizations and placeholders.
     */
    public function trans(null|Countable|int|float|array $number = null, ?array $replace = null): string
    {
        return static::labelFor(value: $this, number: $number, replace: $replace);
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
            'alias' => $this->withAlias(),
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

        $values = array_map(fn (string|self $value) => $value instanceof self ? $value : static::tryFrom(strtolower($value)), $values);

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
    #region overwritable

    /**
     * The default count that should be used in translations. If not specified it's 1.
     */
    public function defaultCount(): Countable|int|float|array
    {
        return 1;
    }

    /**
     * The default replacement array that should be used in translations. If not specified it's [].
     */
    public function defaultReplace(): array
    {
        return [];
    }

    /**
     * Definition of what metadata should be included, empty array by default.
     */
    public function withMeta(): array
    {
        return [];
    }

    /**
     * Definitions of aliases for each case. Can be used to improve parsing.
     */
    public function withAlias(): array
    {
        return [];
    }

    #endregion overwritable
}
