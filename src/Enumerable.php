<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support;

use SimonSchaufi\TYPO3Support\Support\Arrayable;
use SimonSchaufi\TYPO3Support\Support\Jsonable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @extends Arrayable<TKey, TValue>
 * @extends \IteratorAggregate<TKey, TValue>
 */
interface Enumerable extends Arrayable, \Countable, \IteratorAggregate, Jsonable, \JsonSerializable
{
    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @template TMakeKey of array-key
     * @template TMakeValue
     *
     * @param  Arrayable<TMakeKey, TMakeValue>|iterable<TMakeKey, TMakeValue>|null  $items
     * @return static<TMakeKey, TMakeValue>
     */
    public static function make($items = []): static;

    /**
     * Create a new instance by invoking the callback a given amount of times.
     *
     * @param  callable|null  $callback
     */
    public static function times(int $number, callable $callback = null): static;

    /**
     * Create a collection with the given range.
     */
    public static function range(int $from, int $to): static;

    /**
     * Wrap the given value in a collection if applicable.
     *
     * @template TWrapValue
     *
     * @param  iterable<array-key, TWrapValue>|TWrapValue  $value
     * @return static<array-key, TWrapValue>
     */
    public static function wrap($value): static;

    /**
     * Get the underlying items from the given collection if applicable.
     *
     * @template TUnwrapKey of array-key
     * @template TUnwrapValue
     *
     * @param  array<TUnwrapKey, TUnwrapValue>|static<TUnwrapKey, TUnwrapValue>  $value
     * @return array<TUnwrapKey, TUnwrapValue>
     */
    public static function unwrap($value): array;

    /**
     * Create a new instance with no items.
     */
    public static function empty(): static;

    /**
     * Get all items in the enumerable.
     */
    public function all(): array;

    /**
     * Alias for the "avg" method.
     *
     * @param  (callable(TValue): float|int)|string|null  $callback
     * @return float|int|null
     */
    public function average($callback = null);

    /**
     * Get the median of a given key.
     *
     * @param  string|array<array-key, string>|null  $key
     * @return float|int|null
     */
    public function median($key = null);

    /**
     * Get the mode of a given key.
     *
     * @param  string|array<array-key, string>|null  $key
     * @return array<int, float|int>|null
     */
    public function mode($key = null);

    /**
     * Collapse the items into a single enumerable.
     *
     * @return static<int, mixed>
     */
    public function collapse(): static;

    /**
     * Alias for the "contains" method.
     *
     * @param  (callable(TValue, TKey): bool)|TValue|string  $key
     */
    public function some($key, mixed $operator = null, mixed $value = null): bool;

    /**
     * Determine if an item exists, using strict comparison.
     *
     * @param  (callable(TValue): bool)|TValue|array-key  $key
     * @param  TValue|null  $value
     */
    public function containsStrict($key, $value = null): bool;

    /**
     * Get the average value of a given key.
     *
     * @param  (callable(TValue): float|int)|string|null  $callback
     * @return float|int|null
     */
    public function avg($callback = null);

    /**
     * Determine if an item exists in the enumerable.
     *
     * @param  (callable(TValue, TKey): bool)|TValue|string  $key
     */
    public function contains($key, mixed $operator = null, mixed $value = null): bool;

    /**
     * Determine if an item is not contained in the collection.
     */
    public function doesntContain(mixed $key, mixed $operator = null, mixed $value = null): bool;

    /**
     * Cross join with the given lists, returning all possible permutations.
     *
     * @template TCrossJoinKey
     * @template TCrossJoinValue
     *
     * @param  Arrayable<TCrossJoinKey, TCrossJoinValue>|iterable<TCrossJoinKey, TCrossJoinValue>  ...$lists
     * @return static<int, array<int, TValue|TCrossJoinValue>>
     */
    public function crossJoin(...$lists): static;

    /**
     * Get the items that are not present in the given items.
     *
     * @param  Arrayable<array-key, TValue>|iterable<array-key, TValue>  $items
     */
    public function diff($items): static;

    /**
     * Get the items that are not present in the given items, using the callback.
     *
     * @param  Arrayable<array-key, TValue>|iterable<array-key, TValue>  $items
     * @param  callable(TValue, TValue): int  $callback
     */
    public function diffUsing($items, callable $callback): static;

    /**
     * Get the items whose keys and values are not present in the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function diffAssoc($items): static;

    /**
     * Get the items whose keys and values are not present in the given items, using the callback.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     * @param  callable(TKey, TKey): int  $callback
     */
    public function diffAssocUsing($items, callable $callback): static;

    /**
     * Get the items whose keys are not present in the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function diffKeys($items): static;

    /**
     * Get the items whose keys are not present in the given items, using the callback.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     * @param  callable(TKey, TKey): int  $callback
     */
    public function diffKeysUsing($items, callable $callback): static;

    /**
     * Retrieve duplicate items.
     *
     * @param  (callable(TValue): bool)|string|null  $callback
     * @param  bool  $strict
     */
    public function duplicates($callback = null, $strict = false): static;

    /**
     * Retrieve duplicate items using strict comparison.
     *
     * @param  (callable(TValue): bool)|string|null  $callback
     */
    public function duplicatesStrict($callback = null): static;

    /**
     * Execute a callback over each item.
     *
     * @param  callable(TValue, TKey): mixed  $callback
     * @return $this
     */
    public function each(callable $callback): static;

    /**
     * Execute a callback over each nested chunk of items.
     */
    public function eachSpread(callable $callback): static;

    /**
     * Get all items except for those with the specified keys.
     *
     * @param  Enumerable<array-key, TKey>|array<array-key, TKey>  $keys
     */
    public function except($keys): static;

    /**
     * Run a filter over each of the items.
     *
     * @param  (callable(TValue): bool)|null  $callback
     */
    public function filter(callable $callback = null): static;

    /**
     * Filter items by the given key value pair.
     *
     * @param  string  $key
     */
    public function where($key, mixed $operator = null, mixed $value = null): static;

    /**
     * Filter items where the value for the given key is null.
     *
     * @param string|null $key
     */
    public function whereNull(string $key = null): static;

    /**
     * Filter items where the value for the given key is not null.
     *
     * @param string|null $key
     */
    public function whereNotNull(string $key = null): static;

    /**
     * Filter items by the given key value pair using strict comparison.
     */
    public function whereStrict(string $key, mixed $value): static;

    /**
     * Filter items by the given key value pair.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereIn(string $key, $values, bool $strict = false): static;

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereInStrict(string $key, $values): static;

    /**
     * Filter items such that the value of the given key is between the given values.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereBetween(string $key, $values): static;

    /**
     * Filter items such that the value of the given key is not between the given values.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereNotBetween(string $key, $values): static;

    /**
     * Filter items by the given key value pair.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereNotIn(string $key, $values, bool $strict = false): static;

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  Arrayable|iterable  $values
     */
    public function whereNotInStrict(string $key, $values): static;

    /**
     * Filter the items, removing any items that don't match the given type(s).
     *
     * @template TWhereInstanceOf
     *
     * @param  class-string<TWhereInstanceOf>|array<array-key, class-string<TWhereInstanceOf>>  $type
     * @return static<TKey, TWhereInstanceOf>
     */
    public function whereInstanceOf($type): static;

    /**
     * Get the first item from the enumerable passing the given truth test.
     *
     * @template TFirstDefault
     *
     * @param  (callable(TValue,TKey): bool)|null  $callback
     * @param  TFirstDefault|(\Closure(): TFirstDefault)  $default
     * @return TValue|TFirstDefault
     */
    public function first(callable $callback = null, $default = null);

    /**
     * Get the first item by the given key value pair.
     *
     * @param  string  $key
     * @return TValue|null
     */
    public function firstWhere($key, mixed $operator = null, mixed $value = null);

    /**
     * Get a flattened array of the items in the collection.
     *
     * @param  int  $depth
     */
    public function flatten($depth = INF): static;

    /**
     * Flip the values with their keys.
     *
     * @return static<TValue, TKey>
     */
    public function flip(): static;

    /**
     * Get an item from the collection by key.
     *
     * @template TGetDefault
     *
     * @param  TKey  $key
     * @param  TGetDefault|(\Closure(): TGetDefault)  $default
     * @return TValue|TGetDefault
     */
    public function get($key, $default = null);

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param  (callable(TValue, TKey): array-key)|array|string  $groupBy
     * @param  bool  $preserveKeys
     * @return static<array-key, static<array-key, TValue>>
     */
    public function groupBy($groupBy, $preserveKeys = false): static;

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param  (callable(TValue, TKey): array-key)|array|string  $keyBy
     * @return static<array-key, TValue>
     */
    public function keyBy($keyBy): static;

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  TKey|array<array-key, TKey>  $key
     */
    public function has($key): bool;

    /**
     * Determine if any of the keys exist in the collection.
     */
    public function hasAny(mixed $key): bool;

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  callable|string  $value
     * @param  string|null  $glue
     */
    public function implode($value, $glue = null): string;

    /**
     * Intersect the collection with the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function intersect($items): static;

    /**
     * Intersect the collection with the given items, using the callback.
     *
     * @param  Arrayable<array-key, TValue>|iterable<array-key, TValue>  $items
     * @param  callable(TValue, TValue): int  $callback
     */
    public function intersectUsing($items, callable $callback): static;

    /**
     * Intersect the collection with the given items with additional index check.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function intersectAssoc($items): static;

    /**
     * Intersect the collection with the given items with additional index check, using the callback.
     *
     * @param  Arrayable<array-key, TValue>|iterable<array-key, TValue>  $items
     * @param  callable(TValue, TValue): int  $callback
     */
    public function intersectAssocUsing($items, callable $callback): static;

    /**
     * Intersect the collection with the given items by key.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function intersectByKeys($items): static;

    /**
     * Determine if the collection is empty or not.
     */
    public function isEmpty(): bool;

    /**
     * Determine if the collection is not empty.
     */
    public function isNotEmpty(): bool;

    /**
     * Determine if the collection contains a single item.
     */
    public function containsOneItem(): bool;

    /**
     * Join all items from the collection using a string. The final items can use a separate glue string.
     *
     * @param  string  $glue
     * @param  string  $finalGlue
     * @return string
     */
    public function join($glue, $finalGlue = '');

    /**
     * Get the keys of the collection items.
     *
     * @return static<int, TKey>
     */
    public function keys(): static;

    /**
     * Get the last item from the collection.
     *
     * @template TLastDefault
     *
     * @param  (callable(TValue, TKey): bool)|null  $callback
     * @param  TLastDefault|(\Closure(): TLastDefault)  $default
     * @return TValue|TLastDefault
     */
    public function last(callable $callback = null, $default = null);

    /**
     * Run a map over each of the items.
     *
     * @template TMapValue
     *
     * @param  callable(TValue, TKey): TMapValue  $callback
     * @return static<TKey, TMapValue>
     */
    public function map(callable $callback): static;

    /**
     * Run a map over each nested chunk of items.
     */
    public function mapSpread(callable $callback): static;

    /**
     * Run a dictionary map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @template TMapToDictionaryKey of array-key
     * @template TMapToDictionaryValue
     *
     * @param  callable(TValue, TKey): array<TMapToDictionaryKey, TMapToDictionaryValue>  $callback
     * @return static<TMapToDictionaryKey, array<int, TMapToDictionaryValue>>
     */
    public function mapToDictionary(callable $callback): static;

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
    public function mapToGroups(callable $callback): static;

    /**
     * Run an associative map over each of the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @template TMapWithKeysKey of array-key
     * @template TMapWithKeysValue
     *
     * @param  callable(TValue, TKey): array<TMapWithKeysKey, TMapWithKeysValue>  $callback
     * @return static<TMapWithKeysKey, TMapWithKeysValue>
     */
    public function mapWithKeys(callable $callback): static;

    /**
     * Map a collection and flatten the result by a single level.
     *
     * @template TFlatMapKey of array-key
     * @template TFlatMapValue
     *
     * @param  callable(TValue, TKey): (Collection<TFlatMapKey, TFlatMapValue>|array<TFlatMapKey, TFlatMapValue>)  $callback
     * @return static<TFlatMapKey, TFlatMapValue>
     */
    public function flatMap(callable $callback): static;

    /**
     * Map the values into a new class.
     *
     * @template TMapIntoValue
     *
     * @param  class-string<TMapIntoValue>  $class
     * @return static<TKey, TMapIntoValue>
     */
    public function mapInto($class): static;

    /**
     * Merge the collection with the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function merge($items): static;

    /**
     * Recursively merge the collection with the given items.
     *
     * @template TMergeRecursiveValue
     *
     * @param  Arrayable<TKey, TMergeRecursiveValue>|iterable<TKey, TMergeRecursiveValue>  $items
     * @return static<TKey, TValue|TMergeRecursiveValue>
     */
    public function mergeRecursive($items): static;

    /**
     * Create a collection by using this collection for keys and another for its values.
     *
     * @template TCombineValue
     *
     * @param  Arrayable<array-key, TCombineValue>|iterable<array-key, TCombineValue>  $values
     * @return static<TValue, TCombineValue>
     */
    public function combine($values): static;

    /**
     * Union the collection with the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function union($items): static;

    /**
     * Get the min value of a given key.
     *
     * @param  (callable(TValue):mixed)|string|null  $callback
     * @return mixed
     */
    public function min($callback = null);

    /**
     * Get the max value of a given key.
     *
     * @param  (callable(TValue):mixed)|string|null  $callback
     * @return mixed
     */
    public function max($callback = null);

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param  int  $step
     */
    public function nth($step, int $offset = 0): static;

    /**
     * Get the items with the specified keys.
     *
     * @param  Enumerable<array-key, TKey>|array<array-key, TKey>|string  $keys
     */
    public function only($keys): static;

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param  int  $page
     * @param  int  $perPage
     */
    public function forPage($page, $perPage): static;

    /**
     * Partition the collection into two arrays using the given callback or key.
     *
     * @param  (callable(TValue, TKey): bool)|TValue|string  $key
     * @return static<int<0, 1>, static<TKey, TValue>>
     */
    public function partition($key, mixed $operator = null, mixed $value = null): static;

    /**
     * Push all of the given items onto the collection.
     *
     * @param  iterable<array-key, TValue>  $source
     */
    public function concat($source): static;

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
    public function reduce(callable $callback, $initial = null);

    /**
     * Replace the collection items with the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function replace($items): static;

    /**
     * Recursively replace the collection items with the given items.
     *
     * @param  Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     */
    public function replaceRecursive($items): static;

    /**
     * Reverse items order.
     */
    public function reverse(): static;

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param  TValue|callable(TValue,TKey): bool  $value
     * @return TKey|bool
     */
    public function search($value, bool $strict = false);

    /**
     * Create chunks representing a "sliding window" view of the items in the collection.
     *
     * @return static<int, static>
     */
    public function sliding(int $size = 2, int $step = 1): static;

    /**
     * Skip the first {$count} items.
     */
    public function skip(int $count): static;

    /**
     * Get a slice of items from the enumerable.
     *
     * @param  int  $offset
     * @param int|null $length
     */
    public function slice($offset, int $length = null): static;

    /**
     * Split a collection into a certain number of groups.
     *
     * @return static<int, static>
     */
    public function split(int $numberOfGroups): static;

    /**
     * Chunk the collection into chunks of the given size.
     *
     * @param  int  $size
     * @return static<int, static>
     */
    public function chunk($size): static;

    /**
     * Split a collection into a certain number of groups, and fill the first groups completely.
     *
     * @param  int  $numberOfGroups
     * @return static<int, static>
     */
    public function splitIn($numberOfGroups): static;

    /**
     * Sort through each item with a callback.
     *
     * @param  (callable(TValue, TValue): int)|null|int  $callback
     */
    public function sort($callback = null): static;

    /**
     * Sort items in descending order.
     *
     * @param  int  $options
     */
    public function sortDesc($options = SORT_REGULAR): static;

    /**
     * Sort the collection using the given callback.
     *
     * @param  array<array-key, (callable(TValue, TValue): mixed)|(callable(TValue, TKey): mixed)|string|array{string, string}>|(callable(TValue, TKey): mixed)|string  $callback
     * @param  int  $options
     * @param  bool  $descending
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false): static;

    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param  array<array-key, (callable(TValue, TValue): mixed)|(callable(TValue, TKey): mixed)|string|array{string, string}>|(callable(TValue, TKey): mixed)|string  $callback
     * @param  int  $options
     */
    public function sortByDesc($callback, $options = SORT_REGULAR): static;

    /**
     * Sort the collection keys.
     *
     * @param  int  $options
     * @param  bool  $descending
     */
    public function sortKeys($options = SORT_REGULAR, $descending = false): static;

    /**
     * Sort the collection keys in descending order.
     *
     * @param  int  $options
     */
    public function sortKeysDesc($options = SORT_REGULAR): static;

    /**
     * Sort the collection keys using a callback.
     *
     * @param  callable(TKey, TKey): int  $callback
     */
    public function sortKeysUsing(callable $callback): static;

    /**
     * Get the sum of the given values.
     *
     * @param  (callable(TValue): mixed)|string|null  $callback
     * @return mixed
     */
    public function sum($callback = null);

    /**
     * Take the first or last {$limit} items.
     *
     * @param  int  $limit
     * @return static
     */
    public function take($limit);

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param  callable(TValue): mixed  $callback
     * @return $this
     */
    public function tap(callable $callback);

    /**
     * Get the values of a given key.
     *
     * @param  string|array<array-key, string>  $value
     * @param  string|null  $key
     * @return static<int, mixed>
     */
    public function pluck($value, $key = null): static;

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param  (callable(TValue, TKey): bool)|bool|TValue  $callback
     */
    public function reject($callback = true): static;

    /**
     * Convert a flatten "dot" notation array into an expanded array.
     */
    public function undot(): static;

    /**
     * Return only unique items from the collection array.
     *
     * @param  (callable(TValue, TKey): mixed)|string|null  $key
     */
    public function unique($key = null, bool $strict = false): static;

    /**
     * Return only unique items from the collection array using strict comparison.
     *
     * @param  (callable(TValue, TKey): mixed)|string|null  $key
     */
    public function uniqueStrict($key = null): static;

    /**
     * Reset the keys on the underlying array.
     *
     * @return static<int, TValue>
     */
    public function values(): static;

    /**
     * Pad collection to the specified length with a value.
     *
     * @template TPadValue
     *
     * @param  int  $size
     * @param  TPadValue  $value
     * @return static<int, TValue|TPadValue>
     */
    public function pad($size, $value): static;

    /**
     * Get the values iterator.
     *
     * @return \Traversable<TKey, TValue>
     */
    public function getIterator(): \Traversable;

    /**
     * Count the number of items in the collection.
     */
    public function count(): int;

    /**
     * Zip the collection together with one or more arrays.
     *
     * e.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     *
     * @template TZipValue
     *
     * @param  Arrayable<array-key, TZipValue>|iterable<array-key, TZipValue>  ...$items
     * @return static<int, static<int, TValue|TZipValue>>
     */
    public function zip($items): static;

    /**
     * Collect the values into a collection.
     *
     * @return Collection<TKey, TValue>
     */
    public function collect(): Collection;

    /**
     * Get the collection of items as a plain array.
     *
     * @return array<TKey, mixed>
     */
    public function toArray(): array;

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): mixed;

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     */
    public function toJson($options = 0): string;

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString();

    /**
     * Indicate that the model's string representation should be escaped when __toString is invoked.
     *
     * @return $this
     */
    public function escapeWhenCastingToString(bool $escape = true): static;

    /**
     * Dynamically access collection proxies.
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $key);
}
