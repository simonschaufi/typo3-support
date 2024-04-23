<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support\Support;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @see https://github.com/laravel/framework/blob/11.x/src/Illuminate/Contracts/Support/Arrayable.php
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     */
    public function toArray(): array;
}
