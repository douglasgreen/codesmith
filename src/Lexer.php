<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

use DouglasGreen\Exceptions\RegexException;

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

    /**
     * @throws RegexException
     */
    protected function tokenize(): void
    {
        // Define the pattern with the extended and dotall flags (allow comments and whitespace, and dot matches newlines)
        $pattern = '%
            # Matches a valid word or series of words separated by dots
            (?P<word>(?:[a-zA-Z]\\w*\\.)*[a-zA-Z]\\w*) |

            # Matches a number with optional sign, decimal, scientific notation, percentage, or unit
            (?P<number>[-+]?\\d+(?:\\.\\d+)?(?:[eE][-+]?\\d+|\\%|[a-zA-Z]+)?) |

            # Matches a hexadecimal number
            (?P<hex>\#[0-9a-zA-Z]+) |

            # Matches a double-quoted string, including escaped characters
            (?P<string>"(?:\\\\.|[^"])*") |

            # Matches a C-style block comment (/* ... */)
            (?P<comment>/\\*.*?\\*/) |

            # Matches any single character that is not a word character or whitespace
            (?P<mark>[^\\w\\s])
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
            } elseif (! empty($match['hex'])) {
                $type = 'hex';
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
                $parts = preg_split('/\\n/', $value, -1, PREG_SPLIT_NO_EMPTY);
                if ($parts === false) {
                    throw new RegexException('Unable to split token value');
                }

                echo implode(' ', $parts) . '\\n';
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
