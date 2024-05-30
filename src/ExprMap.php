<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class ExprMap
{
    /**
     * @param array<Mapping> $mappings
     */
    public function __construct(
        public array $mappings
    ) {}
}
