<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::PRIVATIZATION,
        // SetList::NAMING,
        SetList::INSTANCEOF,
        SetList::EARLY_RETURN,
        SetList::STRICT_BOOLEANS,

        LevelSetList::UP_TO_PHP_81,
    ]);

    $rectorConfig->rules([
    ]);

    $rectorConfig->skip([
        // CodingStyle
        EncapsedStringsToSprintfRector::class,
        PostIncDecToPreIncDecRector::class,
        NewlineAfterStatementRector::class,
        SeparateMultiUseImportsRector::class,

        // Php80
        ClassPropertyAssignToConstructorPromotionRector::class,

        // TypeDeclaration
        ReturnUnionTypeRector::class,
    ]);
};
