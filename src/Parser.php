<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

use DouglasGreen\Syntax\Exceptions\ParseException;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * @phpstan-type Token array{type: string, value: string}
 * @phpstan-type Mapping array{type: 'mapping', value: string, key: Token}
 * @phpstan-type Map array{type: 'map', mappings: list<Mapping>}
 * @phpstan-type Statement array{
 *     type: 'statement',
 *     comment: ?string,
 *     word: Token,
 *     expressions: list<Expression>
 * }
 * @phpstan-type Block array{type: 'block', statements: list<Statement>}
 * @phpstan-type Expression Token|Block|List|Map
 * @phpstan-type List array{type: 'list', expressions: list<Expression>}
 */
class Parser
{
    /**
     * @var bool
     */
    public $isVerbose;

    /**
     * @var ?Token
     */
    protected $currentToken;

    public function __construct(
        protected Lexer $lexer,
        bool $isVerbose = false
    ) {
        $this->isVerbose = $isVerbose;
        $this->currentToken = $this->lexer->getNextToken();
    }

    /**
     * @param string|list<string> $value
     */
    protected function eat(string $tokenType, string|array $value = null): void
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        if ($this->currentToken['type'] !== $tokenType) {
            throw new ParseException('Unexpected token type: ' . json_encode($this->currentToken));
        }

        if ($value !== null) {
            if (is_string($value) && $this->currentToken['value'] !== $value) {
                throw new ParseException('Unexpected token literal: ' . json_encode($this->currentToken));
            }

            if (is_array($value) && ! in_array($this->currentToken['value'], $value, true)) {
                throw new ParseException('Unexpected token value: ' . json_encode($this->currentToken));
            }
        }

        if ($this->isVerbose) {
            echo 'Parsing ' . $tokenType;
            if ($value !== null) {
                echo ': ' . $this->currentToken['value'];
            }

            echo "\n";
        }

        $this->currentToken = $this->lexer->getNextToken();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function parse(): array
    {
        $nodes = [];
        while ($this->currentToken !== null) {
            $nodes[] = $this->parseStatement();
        }

        return $nodes;
    }

    /**
     * @return Statement
     */
    protected function parseStatement(): array
    {
        if ($this->isVerbose) {
            echo "Parsing statement\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'statement',
        ];

        $node['comment'] = null;
        if ($this->currentToken['type'] === 'comment') {
            $node['comment'] = $this->parseComment();
        }

        $node['word'] = $this->parseWord();

        $node['expressions'] = [];
        while ($this->currentToken !== null) {
            if ($this->currentToken['type'] === 'mark' && $this->currentToken['value'] === ';') {
                break;
            }

            $node['expressions'][] = $this->parseExpression();
        }

        $this->eat('mark', ';');

        return $node;
    }

    /**
     * @return Expression
     */
    protected function parseExpression(): array
    {
        if ($this->isVerbose) {
            echo "Parsing expression\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        switch ($this->currentToken['type']) {
            case 'word':
                return $this->parseWord();
            case 'number':
                return $this->parseNumber();
            case 'string':
                return $this->parseString();
            case 'mark':
                if ($this->currentToken['value'] === '{') {
                    return $this->parseBlock();
                }

                if ($this->currentToken['value'] === '(') {
                    return $this->parseList();
                }

                if ($this->currentToken['value'] === '[') {
                    return $this->parseMap();
                }

                $usedMarks = ['"', '{', '}', '(', ')', '[', ']', ';'];
                if (! in_array($this->currentToken['value'], $usedMarks, true)) {
                    return $this->parseOtherMark();
                }
                // no break
            default:
                throw new ParseException('Unknown expression: ' . json_encode($this->currentToken));
        }
    }

    /**
     * @return Block
     */
    protected function parseBlock(): array
    {
        if ($this->isVerbose) {
            echo "Parsing block\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'block',
            'statements' => [],
        ];
        $this->eat('mark', '{');

        while ($this->currentToken !== null) {
            if ($this->currentToken['type'] === 'mark' && $this->currentToken['value'] === '}') {
                break;
            }

            $node['statements'][] = $this->parseStatement();
        }

        $this->eat('mark', '}');
        return $node;
    }

    /**
     * @return List
     */
    protected function parseList(): array
    {
        if ($this->isVerbose) {
            echo "Parsing list\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'list',
            'expressions' => [],
        ];

        $this->eat('mark', '(');
        while ($this->currentToken !== null) {
            if ($this->currentToken['type'] === 'mark' && $this->currentToken['value'] === ')') {
                break;
            }

            $node['expressions'][] = $this->parseExpression();
        }

        $this->eat('mark', ')');
        return $node;
    }

    /**
     * @return Map
     */
    protected function parseMap(): array
    {
        if ($this->isVerbose) {
            echo "Parsing map\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'map',
            'mappings' => [],
        ];

        $this->eat('mark', '[');
        while ($this->currentToken !== null) {
            if ($this->currentToken['type'] === 'mark' && $this->currentToken['value'] === ']') {
                break;
            }

            $node['mappings'][] = $this->parseMapping();
        }

        $this->eat('mark', ']');
        return $node;
    }

    /**
     * @return Mapping
     */
    protected function parseMapping(): array
    {
        if ($this->isVerbose) {
            echo "Parsing mapping\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'mapping',
            'key' => $this->parseWord(),
        ];

        $this->eat('mark', ':');
        $node['value'] = $this->parseExpression();

        return $node;
    }

    /**
     * @return Token
     */
    protected function parseComment(): array
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'comment',
            'value' => $this->currentToken['value'],
        ];
        $this->eat('comment');
        return $node;
    }

    /**
     * @return Token
     */
    protected function parseNumber(): array
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'number',
            'value' => $this->currentToken['value'],
        ];
        $this->eat('number');
        return $node;
    }

    /**
     * @return Token
     */
    protected function parseOtherMark(): array
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'other',
            'value' => $this->currentToken['value'],
        ];
        $this->eat('mark');
        return $node;
    }

    /**
     * @return Token
     */
    protected function parseString(): array
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'string',
            'value' => $this->currentToken['value'],
        ];
        $this->eat('string');
        return $node;
    }

    /**
     * @return Token
     */
    protected function parseWord(): array
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $node = [
            'type' => 'word',
            'value' => $this->currentToken['value'],
        ];
        $this->eat('word');
        return $node;
    }
}
