<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Scanner
{
    /**
     * @var list<Lexeme>
     */
    protected array $lexemes = [];

    public function __construct(
        protected readonly string $input
    ) {}

    /**
     * @todo Add lexeme type.
     */
    public function scan(): void
    {
        $line = 0;
        $column = 0;
        preg_match_all('/[a-zA-Z_]+|\d+[ \t]|\R|./', $this->input, $matches);
        foreach ($matches[0] as $match) {
            $this->lexemes[] = new Lexeme($match, $line, $column);
            if ($match === PHP_EOL) {
                ++$line;
                $column = 0;
            } else {
                $column += strlen($match);
            }
        }

        var_dump($this->lexemes);
    }
}
