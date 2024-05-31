<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

abstract class Renderer
{
    /**
     * Render the given SyntaxTree to a string in a target language.
     */
    abstract public function render(): string;

    public function __construct(
        protected SyntaxTree $syntaxTree
    ) {}
}
