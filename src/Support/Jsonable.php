<?php

declare(strict_types=1);

namespace SimonSchaufi\TYPO3Support\Support;

/**
 * @see https://github.com/laravel/framework/blob/11.x/src/Illuminate/Contracts/Support/Jsonable.php
 */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}
