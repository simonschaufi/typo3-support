<?php
declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support\Support;

interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
