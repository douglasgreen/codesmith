<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Mapping
{
    public function __construct(
        public Token $token,
        public Expression $expression,
    ) {}
}
