<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

interface RendererInterface
{
    /**
     * Render the given SyntaxTree to a string in a target language.
     */
    public function render(SyntaxTree $syntaxTree): string;
}
