<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support\Traits;

use SimonSchaufi\TYPO3Support\Arr;
use SimonSchaufi\TYPO3Support\Collection;
use SimonSchaufi\TYPO3Support\Enumerable;
use SimonSchaufi\TYPO3Support\HigherOrderCollectionProxy;
use SimonSchaufi\TYPO3Support\Support\Arrayable;
use SimonSchaufi\TYPO3Support\Support\Jsonable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @property-read HigherOrderCollectionProxy $average
 * @property-read HigherOrderCollectionProxy $avg
 * @property-read HigherOrderCollectionProxy $contains
 * @property-read HigherOrderCollectionProxy $doesntContain
 * @property-read HigherOrderCollectionProxy $each
 * @property-read HigherOrderCollectionProxy $every
 * @property-read HigherOrderCollectionProxy $filter
 * @property-read HigherOrderCollectionProxy $first
 * @property-read HigherOrderCollectionProxy $flatMap
 * @property-read HigherOrderCollectionProxy $groupBy
 * @property-read HigherOrderCollectionProxy $keyBy
 * @property-read HigherOrderCollectionProxy $map
 * @property-read HigherOrderCollectionProxy $max
 * @property-read HigherOrderCollectionProxy $min
 * @property-read HigherOrderCollectionProxy $partition
 * @property-read HigherOrderCollectionProxy $percentage
 * @property-read HigherOrderCollectionProxy $reject
 * @property-read HigherOrderCollectionProxy $skipUntil
 * @property-read HigherOrderCollectionProxy $skipWhile
 * @property-read HigherOrderCollectionProxy $some
 * @property-read HigherOrderCollectionProxy $sortBy
 * @property-read HigherOrderCollectionProxy $sortByDesc
 * @property-read HigherOrderCollectionProxy $sum
 * @property-read HigherOrderCollectionProxy $takeUntil
 * @property-read HigherOrderCollectionProxy $takeWhile
 * @property-read HigherOrderCollectionProxy $unique
 * @property-read HigherOrderCollectionProxy $unless
 * @property-read HigherOrderCollectionProxy $until
 * @property-read HigherOrderCollectionProxy $when
 */
trait EnumeratesValues
{
    /**
     * Indicates that the object's string representation should be escaped when __toString is invoked.
     */
    protected bool $escapeWhenCastingToString = false;

    /**
     * The methods that can be proxied.
     *
     * @var array<int, string>
     */
    protected static array $proxies = [
        'average',
        'avg',
        'contains',
        'doesntContain',
        'each',
        'every',
        'filter',
        'first',
        'flatMap',
        'groupBy',
        'keyBy',
        'map',
        'max',
        'min',
        'partition',
        'percentage',
        'reject',
        'skipUntil',
        'skipWhile',
        'some',
        'sortBy',
        'sortByDesc',
        'sum',
        'takeUntil',
        'takeWhile',
        'unique',
        'unless',
        'until',
        'when',
    ];

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @template TMakeKey of array-key
     * @template TMakeValue
     *
     * @param Arrayable<TMakeKey, TMakeValue>|iterable<TMakeKey, TMakeValue>|null  $items
     * @return static<TMakeKey, TMakeValue>
     */
    public static function make($items = []): static
    {
        return new static($items);
    }

    /**
     * Wrap the given value in a collection if applicable.
     *
     * @template TWrapValue
     *
     * @param  iterable<array-key, TWrapValue>|TWrapValue  $value
     * @return static<array-key, TWrapValue>
     */
    public static function wrap($value): static
    {
        return $value instanceof Enumerable
            ? new static($value)
            : new static(Arr::wrap($value));
    }

    /**
     * Get the underlying items from the given collection if applicable.
     *
     * @template TUnwrapKey of array-key
     * @template TUnwrapValue
     *
     * @param  array<TUnwrapKey, TUnwrapValue>|static<TUnwrapKey, TUnwrapValue>  $value
     * @return array<TUnwrapKey, TUnwrapValue>
     */
    public static function unwrap($value): array
    {
        return $value instanceof Enumerable ? $value->all() : $value;
    }

    /**
     * Create a new instance with no items.
     */
    public static function empty(): static
    {
        return new static([]);
    }

    /**
     * Create a new collection by invoking the callback a given amount of times.
     *
     * @template TTimesValue
     *
     * @param  (callable(int): TTimesValue)|null  $callback
     * @return static<int, TTimesValue>
     */
    public static function times(int $number, ?callable $callback = null): static
    {
        if ($number < 1) {
            return new static;
        }

        return static::range(1, $number)
            ->unless($callback == null)
            ->map($callback);
    }

    /**
     * Alias for the "avg" method.
     *
     * @param  (callable(TValue): float|int)|string|null  $callback
     * @return float|int|null
     */
    public function average($callback = null)
    {
        return $this->avg($callback);
    }

    /**
     * Alias for the "contains" method.
     *
     * @param  (callable(TValue, TKey): bool)|TValue|string  $key
     */
    public function some($key, mixed $operator = null, mixed $value = null): bool
    {
        return $this->contains(...func_get_args());
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable(TValue, TKey): mixed  $callback
     * @return $this
     */
    public function each(callable $callback): static
    {
        foreach ($this as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Execute a callback over each nested chunk of items.
     *
     * @param  callable(...mixed): mixed  $callback
     */
    public function eachSpread(callable $callback): static
    {
        return $this->each(static function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Get the first item by the given key value pair.
     *
     * @param  callable|string  $key
     * @return TValue|null
     */
    public function firstWhere($key, mixed $operator = null, mixed $value = null)
    {
        return $this->first($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Ensure that every item in the collection is of the expected type.
     *
     * @template TEnsureOfType
     *
     * @param  class-string<TEnsureOfType>|array<array-key, class-string<TEnsureOfType>>  $type
     * @return static<TKey, TEnsureOfType>
     *
     * @throws \UnexpectedValueException
     */
    public function ensure($type): static
    {
        $allowedTypes = is_array($type) ? $type : [$type];

        return $this->each(static function ($item, $index) use ($allowedTypes) {
            $itemType = get_debug_type($item);

            foreach ($allowedTypes as $allowedType) {
                if ($itemType === $allowedType || $item instanceof $allowedType) {
                    return true;
                }
            }

            throw new \UnexpectedValueException(
                sprintf("Collection should only include [%s] items, but '%s' found at position %d.", implode(', ', $allowedTypes), $itemType, $index)
            );
        });
    }

    /**
     * Determine if the collection is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Run a map over each nested chunk of items.
     *
     * @template TMapSpreadValue
     *
     * @param  callable(mixed...): TMapSpreadValue  $callback
     * @return static<TKey, TMapSpreadValue>
     */
    public function mapSpread(callable $callback): static
    {
        return $this->map(static function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Run a grouping map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @template TMapToGroupsKey of array-key
     * @template TMapToGroupsValue
     *
     * @param  callable(TValue, TKey): array<TMapToGroupsKey, TMapToGroupsValue>  $callback
     * @return static<TMapToGroupsKey, static<int, TMapToGroupsValue>>
     */
    public function mapToGroups(callable $callback): static
    {
        $groups = $this->mapToDictionary($callback);

        return $groups->map([$this, 'make']);
    }

    /**
     * Map a collection and flatten the result by a single level.
     *
     * @template TFlatMapKey of array-key
     * @template TFlatMapValue
     *
     * @param  callable(TValue, TKey): (Collection<TFlatMapKey, TFlatMapValue>|array<TFlatMapKey, TFlatMapValue>)  $callback
     * @return static<TFlatMapKey, TFlatMapValue>
     */
    public function flatMap(callable $callback): static
    {
        return $this->map($callback)->collapse();
    }

    /**
     * Map the values into a new class.
     *
     * @template TMapIntoValue
     *
     * @param  class-string<TMapIntoValue>  $class
     * @return static<TKey, TMapIntoValue>
     */
    public function mapInto($class): static
    {
        if (is_subclass_of($class, \BackedEnum::class)) {
            return $this->map(static fn($value, $key): \BackedEnum => $class::from($value));
        }

        return $this->map(static fn($value, $key): object => new $class($value, $key));
    }

    /**
     * Get the min value of a given key.
     *
     * @param  (callable(TValue):mixed)|string|null  $callback
     * @return mixed
     */
    public function min($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->map(static fn($value) => $callback($value))
            ->filter(static fn($value): bool => ! is_null($value))
            ->reduce(static fn($result, $value) => is_null($result) || $value < $result ? $value : $result);
    }

    /**
     * Get the max value of a given key.
     *
     * @param  (callable(TValue):mixed)|string|null  $callback
     * @return mixed
     */
    public function max($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->filter(static fn($value): bool => ! is_null($value))->reduce(static function ($result, $item) use ($callback) {
            $value = $callback($item);

            return is_null($result) || $value > $result ? $value : $result;
        });
    }

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param  int  $page
     * @param  int  $perPage
     */
    public function forPage($page, $perPage): static
    {
        $offset = max(0, ($page - 1) * $perPage);

        return $this->slice($offset, $perPage);
    }

    /**
     * Partition the collection into two arrays using the given callback or key.
     *
     * @param  (callable(TValue, TKey): bool)|TValue|string  $key
     * @param  TValue|string|null  $operator
     * @param  TValue|null  $value
     * @return static<int<0, 1>, static<TKey, TValue>>
     */
    public function partition($key, $operator = null, $value = null): static
    {
        $passed = [];
        $failed = [];

        $callback = func_num_args() === 1
            ? $this->valueRetriever($key)
            : $this->operatorForWhere(...func_get_args());

        foreach ($this as $k => $item) {
            if ($callback($item, $k)) {
                $passed[$k] = $item;
            } else {
                $failed[$k] = $item;
            }
        }

        return new static([new static($passed), new static($failed)]);
    }

    /**
     * Calculate the percentage of items that pass a given truth test.
     *
     * @param  (callable(TValue, TKey): bool)  $callback
     */
    public function percentage(callable $callback, int $precision = 2): ?float
    {
        if ($this->isEmpty()) {
            return null;
        }

        return round(
            $this->filter($callback)->count() / $this->count() * 100,
            $precision
        );
    }

    /**
     * Get the sum of the given values.
     *
     * @param  (callable(TValue): mixed)|string|null  $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        $callback = is_null($callback)
            ? $this->identity()
            : $this->valueRetriever($callback);

        return $this->reduce(static fn($result, $item): float|int|array => $result + $callback($item), 0);
    }

    /**
     * Apply the callback if the collection is empty.
     *
     * @template TWhenEmptyReturnType
     *
     * @param  (callable($this): TWhenEmptyReturnType)  $callback
     * @param  (callable($this): TWhenEmptyReturnType)|null  $default
     * @return $this|TWhenEmptyReturnType
     */
    public function whenEmpty(callable $callback, ?callable $default = null): self
    {
        return $this->when($this->isEmpty(), $callback, $default);
    }

    /**
     * Apply the callback if the collection is not empty.
     *
     * @template TWhenNotEmptyReturnType
     *
     * @param  callable($this): TWhenNotEmptyReturnType  $callback
     * @param  (callable($this): TWhenNotEmptyReturnType)|null  $default
     * @return $this|TWhenNotEmptyReturnType
     */
    public function whenNotEmpty(callable $callback, ?callable $default = null): self
    {
        return $this->when($this->isNotEmpty(), $callback, $default);
    }

    /**
     * Apply the callback unless the collection is empty.
     *
     * @template TUnlessEmptyReturnType
     *
     * @param  callable($this): TUnlessEmptyReturnType  $callback
     * @param  (callable($this): TUnlessEmptyReturnType)|null  $default
     * @return $this|TUnlessEmptyReturnType
     */
    public function unlessEmpty(callable $callback, ?callable $default = null): self
    {
        return $this->whenNotEmpty($callback, $default);
    }

    /**
     * Apply the callback unless the collection is not empty.
     *
     * @template TUnlessNotEmptyReturnType
     *
     * @param  callable($this): TUnlessNotEmptyReturnType  $callback
     * @param  (callable($this): TUnlessNotEmptyReturnType)|null  $default
     * @return $this|TUnlessNotEmptyReturnType
     */
    public function unlessNotEmpty(callable $callback, ?callable $default = null): self
    {
        return $this->whenEmpty($callback, $default);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  callable|string  $key
     */
    public function where($key, mixed $operator = null, mixed $value = null): static
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Filter items where the value for the given key is null.
     *
     * @param string|null $key
     */
    public function whereNull(string $key = null): static
    {
        return $this->whereStrict($key, null);
    }

    /**
     * Filter items where the value for the given key is not null.
     *
     * @param string|null $key
     */
    public function whereNotNull(string $key = null): static
    {
        return $this->where($key, '!==', null);
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     */
    public function whereStrict(string $key, mixed $value): static
    {
        return $this->where($key, '===', $value);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereIn(string $key, $values, bool $strict = false): static
    {
        $values = $this->getArrayableItems($values);

        return $this->filter(static fn($item): bool => in_array(data_get($item, $key), $values, $strict));
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereInStrict(string $key, $values): static
    {
        return $this->whereIn($key, $values, true);
    }

    /**
     * Filter items such that the value of the given key is between the given values.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereBetween(string $key, $values): static
    {
        return $this->where($key, '>=', reset($values))->where($key, '<=', end($values));
    }

    /**
     * Filter items such that the value of the given key is not between the given values.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereNotBetween(string $key, $values): static
    {
        return $this->filter(
            static fn($item): bool => data_get($item, $key) < reset($values) || data_get($item, $key) > end($values)
        );
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereNotIn(string $key, $values, bool $strict = false): static
    {
        $values = $this->getArrayableItems($values);

        return $this->reject(static fn($item): bool => in_array(data_get($item, $key), $values, $strict));
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param Arrayable|iterable  $values
     */
    public function whereNotInStrict(string $key, $values): static
    {
        return $this->whereNotIn($key, $values, true);
    }

    /**
     * Filter the items, removing any items that don't match the given type(s).
     *
     * @template TWhereInstanceOf
     *
     * @param  class-string<TWhereInstanceOf>|array<array-key, class-string<TWhereInstanceOf>>  $type
     * @return static<TKey, TWhereInstanceOf>
     */
    public function whereInstanceOf($type): static
    {
        return $this->filter(static function ($value) use ($type) : bool {
            if (is_array($type)) {
                foreach ($type as $classType) {
                    if ($value instanceof $classType) {
                        return true;
                    }
                }

                return false;
            }

            return $value instanceof $type;
        });
    }

    /**
     * Reduce the collection to a single value.
     *
     * @template TReduceInitial
     * @template TReduceReturnType
     *
     * @param  callable(TReduceInitial|TReduceReturnType, TValue, TKey): TReduceReturnType  $callback
     * @param  TReduceInitial  $initial
     * @return TReduceReturnType
     */
    public function reduce(callable $callback, $initial = null)
    {
        $result = $initial;

        foreach ($this as $key => $value) {
            $result = $callback($result, $value, $key);
        }

        return $result;
    }

    /**
     * Reduce the collection to multiple aggregate values.
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function reduceSpread(callable $callback, mixed ...$initial)
    {
        $result = $initial;

        foreach ($this as $key => $value) {
            $result = call_user_func_array($callback, array_merge($result, [$value, $key]));

            if (! is_array($result)) {
                throw new \UnexpectedValueException(sprintf(
                    "%s::reduceSpread expects reducer to return an array, but got a '%s' instead.",
                    static::class, gettype($result)
                ));
            }
        }

        return $result;
    }

    /**
     * Reduce an associative collection to a single value.
     *
     * @template TReduceWithKeysInitial
     * @template TReduceWithKeysReturnType
     *
     * @param  callable(TReduceWithKeysInitial|TReduceWithKeysReturnType, TValue, TKey): TReduceWithKeysReturnType  $callback
     * @param  TReduceWithKeysInitial  $initial
     * @return TReduceWithKeysReturnType
     */
    public function reduceWithKeys(callable $callback, $initial = null)
    {
        return $this->reduce($callback, $initial);
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param  (callable(TValue, TKey): bool)|bool|TValue  $callback
     */
    public function reject($callback = true): static
    {
        $useAsCallable = $this->useAsCallable($callback);

        return $this->filter(static fn($value, $key): bool => $useAsCallable
            ? ! $callback($value, $key)
            : $value != $callback);
    }

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param  callable($this): mixed  $callback
     * @return $this
     */
    public function tap(callable $callback): static
    {
        $callback($this);

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     *
     * @param  (callable(TValue, TKey): mixed)|string|null  $key
     */
    public function unique($key = null, bool $strict = false): static
    {
        $callback = $this->valueRetriever($key);

        $exists = [];

        return $this->reject(static function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }

            $exists[] = $id;
        });
    }

    /**
     * Return only unique items from the collection array using strict comparison.
     *
     * @param  (callable(TValue, TKey): mixed)|string|null  $key
     */
    public function uniqueStrict($key = null): static
    {
        return $this->unique($key, true);
    }

    /**
     * Collect the values into a collection.
     *
     * @return Collection<TKey, TValue>
     */
    public function collect(): Collection
    {
        return new Collection($this->all());
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array<TKey, mixed>
     */
    public function toArray(): array
    {
        return $this->map(static fn($value) => $value instanceof Arrayable ? $value->toArray() : $value)->all();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array<TKey, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_map(static function ($value) {
            if ($value instanceof \JsonSerializable) {
                return $value->jsonSerialize();
            }

            if ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            }

            if ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->all());
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->escapeWhenCastingToString
            ? htmlspecialchars($this->toJson())
            : $this->toJson();
    }

    /**
     * Indicate that the model's string representation should be escaped when __toString is invoked.
     *
     * @return $this
     */
    public function escapeWhenCastingToString(bool $escape = true): static
    {
        $this->escapeWhenCastingToString = $escape;

        return $this;
    }

    /**
     * Add a method to the list of proxied methods.
     *
     * @param  string  $method
     */
    public static function proxy($method): void
    {
        static::$proxies[] = $method;
    }

    /**
     * Dynamically access collection proxies.
     *
     * @return mixed
     *
     * @throws \Exception
     * @noinspection MagicMethodsValidityInspection
     */
    public function __get(string $key)
    {
        if (! in_array($key, static::$proxies)) {
            throw new \Exception(sprintf('Property [%s] does not exist on this collection instance.', $key));
        }

        return new HigherOrderCollectionProxy($this, $key);
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @return array<TKey, TValue>
     */
    protected function getArrayableItems(mixed $items): array
    {
        if (is_array($items)) {
            return $items;
        }

        return match (true) {
            $items instanceof \WeakMap => throw new \InvalidArgumentException('Collections can not be created using instances of WeakMap.'),
            $items instanceof Enumerable => $items->all(),
            $items instanceof Arrayable => $items->toArray(),
            $items instanceof \Traversable => iterator_to_array($items),
            $items instanceof Jsonable => json_decode($items->toJson(), true, 512, JSON_THROW_ON_ERROR),
            $items instanceof \JsonSerializable => (array) $items->jsonSerialize(),
            $items instanceof \UnitEnum => [$items],
            default => (array) $items,
        };
    }

    /**
     * Get an operator checker callback.
     *
     * @param  callable|string  $key
     * @param  string|null  $operator
     * @return \Closure
     */
    protected function operatorForWhere($key, $operator = null, mixed $value = null)
    {
        if ($this->useAsCallable($key)) {
            return $key;
        }

        if (func_num_args() === 1) {
            $value = true;

            $operator = '=';
        }

        if (func_num_args() === 2) {
            $value = $operator;

            $operator = '=';
        }

        return static function ($item) use ($key, $operator, $value) {
            $retrieved = data_get($item, $key);

            $strings = array_filter([$retrieved, $value], static fn($value): bool => is_string($value) || (is_object($value) && method_exists($value, '__toString')));

            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) == 1) {
                return in_array($operator, ['!=', '<>', '!==']);
            }

            switch ($operator) {
                default:
                case '=':
                case '==':  return $retrieved == $value;
                case '!=':
                case '<>':  return $retrieved != $value;
                case '<':   return $retrieved < $value;
                case '>':   return $retrieved > $value;
                case '<=':  return $retrieved <= $value;
                case '>=':  return $retrieved >= $value;
                case '===': return $retrieved === $value;
                case '!==': return $retrieved !== $value;
                case '<=>': return $retrieved <=> $value;
            }
        };
    }

    /**
     * Determine if the given value is callable, but not a string.
     */
    protected function useAsCallable(mixed $value): bool
    {
        return ! is_string($value) && is_callable($value);
    }

    /**
     * Get a value retrieving callback.
     *
     * @param  callable|string|null  $value
     * @return callable
     */
    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return static fn ($item) => data_get($item, $value);
    }

    /**
     * Make a function to check an item's equality.
     *
     * @return \Closure(mixed): bool
     */
    protected function equality(mixed $value)
    {
        return static fn ($item): bool => $item === $value;
    }

    /**
     * Make a function using another function, by negating its result.
     *
     * @return \Closure
     */
    protected function negate(\Closure $callback)
    {
        return static fn (...$params): bool => ! $callback(...$params);
    }

    /**
     * Make a function that returns what's passed to it.
     *
     * @return \Closure(TValue): TValue
     */
    protected function identity()
    {
        return static fn ($value) => $value;
    }
}
