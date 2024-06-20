<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Lexeme
{
    public function __construct(
        protected readonly string $text,
        protected readonly int $line,
        protected readonly int $column,
    ) {}
}
