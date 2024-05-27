<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class Token
{
    public function __construct(
        public string $type,
        public string $value
    ) {}
}
