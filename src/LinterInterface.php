<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

interface LinterInterface
{
    /**
     * Run checks on the given SyntaxTree.
     *
     * @return list<string> An array of issues found
     */
    public function lint(SyntaxTree $syntaxTree): array;
}
