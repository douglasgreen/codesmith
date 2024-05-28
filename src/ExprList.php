<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class ExprList
{
    /**
     * @param array<Expression> $expressions
     */
    public function __construct(
        public array $expressions
    ) {}
}
