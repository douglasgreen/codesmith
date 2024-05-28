<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

use DouglasGreen\Syntax\Exceptions\RegexException;

class Lexer
{
    /**
     * @var list<Token>
     */
    protected array $tokens = [];

    protected int $position = 0;

    public function __construct(
        protected string $input,
        protected bool $isVerbose = false
    ) {
        $this->tokenize();
    }

    protected function tokenize(): void
    {
        $pattern = '%
            (?P<word>\\b[a-zA-Z_]\\w*\\b) | # Word tokens
            (?P<number>\\b\\d[\\d_]*\\b) |  # Numeric tokens
            (?P<string>"(?:\\\\.|[^"])*") | # String tokens
            (?P<comment>/\\*.*?\\*/) |      # Comment tokens
            (?P<mark>[^\\w\\s])             # Punctuation tokens
        %xs';

        $result = preg_match_all($pattern, $this->input, $matches, PREG_SET_ORDER);
        if ($result === false) {
            throw new RegexException('Failure to match tokens');
        }

        foreach ($matches as $match) {
            if (! empty($match['word'])) {
                $type = 'word';
            } elseif (! empty($match['number'])) {
                $type = 'number';
            } elseif (! empty($match['string'])) {
                $type = 'string';
            } elseif (! empty($match['comment'])) {
                $type = 'comment';
            } elseif (! empty($match['mark'])) {
                $type = 'mark';
            } else {
                throw new RegexException('Unrecognized token type');
            }

            $value = $match[$type];
            $this->tokens[] = new Token($type, $value);
            if ($this->isVerbose) {
                echo sprintf('Token: %s, Value: ', $type);
                $parts = preg_split("/\n/", $value, -1, PREG_SPLIT_NO_EMPTY);
                if ($parts === false) {
                    throw new RegexException('Unable to split token value');
                }

                echo implode(' ', $parts) . "\n";
            }
        }
    }

    /**
     * @return ?Token
     */
    public function getNextToken()
    {
        if ($this->position < count($this->tokens)) {
            return $this->tokens[$this->position++];
        }

        return null;
    }

    public function reset(): void
    {
        $this->position = 0;
    }
}
