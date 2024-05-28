<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax\Tests;

use DouglasGreen\Syntax\Lexer;
use DouglasGreen\Syntax\Token;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function testTokenizeInput(): void
    {
        $input = 'word 123 "string" /* comment */ ;';
        $lexer = new Lexer($input);

        $tokens = [
            new Token('word', 'word'),
            new Token('number', '123'),
            new Token('string', '"string"'),
            new Token('comment', '/* comment */'),
            new Token('mark', ';'),
        ];

        foreach ($tokens as $expectedToken) {
            $token = $lexer->getNextToken();
            $this->assertInstanceOf(Token::class, $token);
            $this->assertSame($expectedToken->type, $token->type);
            $this->assertSame($expectedToken->value, $token->value);
        }

        $this->assertNull($lexer->getNextToken());
    }

    public function testGetNextToken(): void
    {
        $input = 'word';
        $lexer = new Lexer($input);

        $token = $lexer->getNextToken();
        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame('word', $token->type);
        $this->assertSame('word', $token->value);

        $this->assertNull($lexer->getNextToken());
    }

    public function testReset(): void
    {
        $input = 'word';
        $lexer = new Lexer($input);

        $lexer->getNextToken();
        $lexer->reset();

        $token = $lexer->getNextToken();
        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame('word', $token->type);
        $this->assertSame('word', $token->value);
    }
}
