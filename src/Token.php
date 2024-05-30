<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Token
{
    public function __construct(
        public string $type,
        public string $value
    ) {}
}
