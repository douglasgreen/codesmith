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

    public function testTokenizeDotSeparatedWords(): void
    {
        $input = 'this.is.a.dot.separated.word';
        $lexer = new Lexer($input);

        $token = $lexer->getNextToken();
        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame('word', $token->type);
        $this->assertSame('this.is.a.dot.separated.word', $token->value);

        $this->assertNull($lexer->getNextToken());
    }

    public function testTokenizeSignedIntegers(): void
    {
        $input = '-123 +456';
        $lexer = new Lexer($input);

        $tokens = [new Token('number', '-123'), new Token('number', '+456')];

        foreach ($tokens as $expectedToken) {
            $token = $lexer->getNextToken();
            $this->assertInstanceOf(Token::class, $token);
            $this->assertSame($expectedToken->type, $token->type);
            $this->assertSame($expectedToken->value, $token->value);
        }

        $this->assertNull($lexer->getNextToken());
    }

    public function testTokenizeFloats(): void
    {
        $input = '3.14 -0.5 +2.0e-3';
        $lexer = new Lexer($input);

        $tokens = [new Token('number', '3.14'), new Token('number', '-0.5'), new Token('number', '+2.0e-3')];

        foreach ($tokens as $expectedToken) {
            $token = $lexer->getNextToken();
            $this->assertInstanceOf(Token::class, $token);
            $this->assertSame($expectedToken->type, $token->type);
            $this->assertSame($expectedToken->value, $token->value);
        }

        $this->assertNull($lexer->getNextToken());
    }

    public function testTokenizePercents(): void
    {
        $input = '10% -42.3%';
        $lexer = new Lexer($input);

        $tokens = [new Token('number', '10%'), new Token('number', '-42.3%')];

        foreach ($tokens as $expectedToken) {
            $token = $lexer->getNextToken();
            $this->assertInstanceOf(Token::class, $token);
            $this->assertSame($expectedToken->type, $token->type);
            $this->assertSame($expectedToken->value, $token->value);
        }

        $this->assertNull($lexer->getNextToken());
    }

    public function testTokenizeUnits(): void
    {
        $input = '60px 70rem 1.3GB';
        $lexer = new Lexer($input);

        $tokens = [new Token('number', '60px'), new Token('number', '70rem'), new Token('number', '1.3GB')];

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
