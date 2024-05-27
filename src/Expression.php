<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class Expression
{
    public string $type = 'expression';

    public function __construct(
        public Token|ExprList|ExprMap $expression
    ) {}
}
