<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class SyntaxTree
{
    /**
     * @param list<Statement> $statements
     */
    public function __construct(
        public array $statements
    ) {}
}
