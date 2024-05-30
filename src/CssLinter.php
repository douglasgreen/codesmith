<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class CssLinter extends Linter
{
    /**
     * @return list<string>
     */
    public function lint(): array
    {
        foreach ($this->syntaxTree->statements as $statement) {
            var_dump($statement);
        }

        return [];
    }
}
