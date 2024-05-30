<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Expression
{
    public function __construct(
        public Token|ExprList|ExprMap $expression
    ) {}
}
