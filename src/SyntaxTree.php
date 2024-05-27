<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class SyntaxTree
{
    /**
     * @param list<Statement> $statements
     */
    public function __construct(
        public array $statements
    ) {}
}
