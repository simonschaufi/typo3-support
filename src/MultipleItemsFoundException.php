<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support;

use RuntimeException;

class MultipleItemsFoundException extends RuntimeException
{
    /**
     * The number of items found.
     */
    public int $count;

    /**
     * Create a new exception instance.
     *
     * @param  int  $count
     * @param \Throwable|null $previous
     * @return void
     */
    public function __construct($count, int $code = 0, \Throwable $previous = null)
    {
        $this->count = $count;

        parent::__construct($count . ' items were found.', $code, $previous);
    }

    /**
     * Get the number of items found.
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
