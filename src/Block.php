<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class Block
{
    /**
     * @param array<Statement> $statements
     */
    public function __construct(
        public array $statements
    ) {}
}
