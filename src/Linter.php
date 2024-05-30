<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

abstract class Linter
{
    public function __construct(
        protected SyntaxTree $syntaxTree
    ) {}

    /**
     * Run checks on the given SyntaxTree.
     *
     * @return list<string> An array of issues found
     */
    abstract public function lint(): array;
}
