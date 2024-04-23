<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support;

/**
 * @mixin Collection
 * @see https://github.com/laravel/framework/blob/11.x/src/Illuminate/Collections/HigherOrderCollectionProxy.php
 */
class HigherOrderCollectionProxy
{
    /**
     * The collection being operated on.
     */
    protected Collection $collection;

    /**
     * The method being proxied.
     */
    protected string $method;

    /**
     * Create a new proxy instance.
     */
    public function __construct(Collection $collection, string $method)
    {
        $this->collection = $collection;
        $this->method = $method;
    }

    /**
     * Proxy accessing an attribute onto the collection items.
     *
     * @return mixed
     * @noinspection MagicMethodsValidityInspection
     */
    public function __get(string $key)
    {
        return $this->collection->{$this->method}(static fn($value) => is_array($value) ? $value[$key] : $value->{$key});
    }

    /**
     * Proxy a method call onto the collection items.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->collection->{$this->method}(static fn($value) => $value->{$method}(...$parameters));
    }
}
