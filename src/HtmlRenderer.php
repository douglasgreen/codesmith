<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class HtmlRenderer extends Renderer
{
    public function render(): string
    {
        foreach ($this->syntaxTree->statements as $statement) {
            var_dump($statement);
        }

        return '';
    }
}
