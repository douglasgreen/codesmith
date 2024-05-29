<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

use DouglasGreen\Exceptions\ParseException;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
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
     *
     * @throws ParseException
     */
    protected function eat(string $tokenType, string|array $value = null): void
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        if ($this->currentToken->type !== $tokenType) {
            throw new ParseException('Unexpected token type: ' . json_encode($this->currentToken));
        }

        if ($value !== null) {
            if (is_string($value) && $this->currentToken->value !== $value) {
                throw new ParseException('Unexpected token literal: ' . json_encode($this->currentToken));
            }

            if (is_array($value) && ! in_array($this->currentToken->value, $value, true)) {
                throw new ParseException('Unexpected token value: ' . json_encode($this->currentToken));
            }
        }

        if ($this->isVerbose) {
            echo 'Parsing ' . $tokenType;
            if ($value !== null) {
                echo ': ' . $this->currentToken->value;
            }

            echo "\n";
        }

        $this->currentToken = $this->lexer->getNextToken();
    }

    public function parse(): SyntaxTree
    {
        $nodes = [];
        while ($this->currentToken !== null) {
            $nodes[] = $this->parseStatement();
        }

        return new SyntaxTree($nodes);
    }

    /**
     * @throws ParseException
     */
    protected function parseStatement(): Statement
    {
        if ($this->isVerbose) {
            echo "Parsing statement\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $comment = null;
        if ($this->currentToken->type === 'comment') {
            $comment = $this->parseComment();
        }

        $word = $this->parseWord();

        $expressions = [];
        $block = null;
        while ($this->currentToken !== null) {
            if ($this->currentToken->type === 'mark' && $this->currentToken->value === ';') {
                $this->eat('mark', ';');
                break;
            }

            if ($this->currentToken->type === 'mark' && $this->currentToken->value === '{') {
                $block = $this->parseBlock();
                break;
            }

            $expressions[] = $this->parseExpression();
        }

        return new Statement($comment, $word, $expressions, $block);
    }

    /**
     * @throws ParseException
     */
    protected function parseExpression(): Expression
    {
        if ($this->isVerbose) {
            echo "Parsing expression\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        switch ($this->currentToken->type) {
            case 'word':
                return new Expression($this->parseWord());
            case 'number':
                return new Expression($this->parseNumber());
            case 'string':
                return new Expression($this->parseString());
            case 'mark':
                if ($this->currentToken->value === '(') {
                    return new Expression($this->parseList());
                }

                if ($this->currentToken->value === '[') {
                    return new Expression($this->parseMap());
                }

                $usedMarks = ['"', '{', '}', '(', ')', '[', ']', ';'];
                if (! in_array($this->currentToken->value, $usedMarks, true)) {
                    return new Expression($this->parseOtherMark());
                }
                // no break
            default:
                throw new ParseException('Unknown expression: ' . json_encode($this->currentToken));
        }
    }

    /**
     * @throws ParseException
     */
    protected function parseBlock(): Block
    {
        if ($this->isVerbose) {
            echo "Parsing block\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $statements = [];
        $this->eat('mark', '{');

        while ($this->currentToken !== null) {
            if ($this->currentToken->type === 'mark' && $this->currentToken->value === '}') {
                $this->eat('mark', '}');
                break;
            }

            $statements[] = $this->parseStatement();
        }

        return new Block($statements);
    }

    /**
     * @throws ParseException
     */
    protected function parseList(): ExprList
    {
        if ($this->isVerbose) {
            echo "Parsing list\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $expressions = [];
        $this->eat('mark', '(');

        while ($this->currentToken !== null) {
            if ($this->currentToken->type === 'mark' && $this->currentToken->value === ')') {
                $this->eat('mark', ')');
                break;
            }

            $expressions[] = $this->parseExpression();
        }

        return new ExprList($expressions);
    }

    /**
     * @throws ParseException
     */
    protected function parseMap(): ExprMap
    {
        if ($this->isVerbose) {
            echo "Parsing map\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $mappings = [];
        $this->eat('mark', '[');

        while ($this->currentToken !== null) {
            if ($this->currentToken->type === 'mark' && $this->currentToken->value === ']') {
                $this->eat('mark', ']');
                break;
            }

            $mappings[] = $this->parseMapping();
        }

        return new ExprMap($mappings);
    }

    /**
     * @throws ParseException
     */
    protected function parseMapping(): Mapping
    {
        if ($this->isVerbose) {
            echo "Parsing mapping\n";
        }

        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $token = $this->parseWord();
        $this->eat('mark', ':');
        $expression = $this->parseExpression();

        return new Mapping($token, $expression);
    }

    /**
     * @throws ParseException
     */
    protected function parseComment(): Token
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $value = $this->currentToken->value;
        $this->eat('comment');
        return new Token('comment', $value);
    }

    /**
     * @throws ParseException
     */
    protected function parseNumber(): Token
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $value = $this->currentToken->value;
        $this->eat('number');
        return new Token('number', $value);
    }

    /**
     * @throws ParseException
     */
    protected function parseOtherMark(): Token
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $value = $this->currentToken->value;
        $this->eat('mark');
        return new Token('other', $value);
    }

    /**
     * @throws ParseException
     */
    protected function parseString(): Token
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $value = $this->currentToken->value;
        $this->eat('string');
        return new Token('string', $value);
    }

    /**
     * @throws ParseException
     */
    protected function parseWord(): Token
    {
        if ($this->currentToken === null) {
            throw new ParseException('Out of tokens');
        }

        $value = $this->currentToken->value;
        $this->eat('word');
        return new Token('word', $value);
    }
}
