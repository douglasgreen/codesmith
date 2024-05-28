<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class Mapping
{
    public function __construct(
        public Token $token,
        public Expression $expression
    ) {}
}
