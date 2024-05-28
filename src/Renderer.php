<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

abstract class Renderer
{
    public function __construct(
        protected SyntaxTree $syntaxTree
    ) {}

    /**
     * Render the given SyntaxTree to a string in a target language.
     */
    abstract public function render(): string;
}
