<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class ExprList
{
    /**
     * @param array<Expression> $expressions
     */
    public function __construct(
        public array $expressions
    ) {}
}
