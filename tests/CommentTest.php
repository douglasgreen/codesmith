<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax\Tests;

use DouglasGreen\Syntax\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testSingleLineComment(): void
    {
        $commentText = '/* This is a single line comment */';
        $comment = new Comment($commentText);

        $expected = ['This is a single line comment'];
        $this->assertSame($expected, $comment->getLines());
    }

    public function testMultiLineComment(): void
    {
        $commentText = <<<EOD
            /*
             * This is a
             * multiline comment
             */
            EOD;
        $comment = new Comment($commentText);

        $expected = ['This is a', 'multiline comment'];
        $this->assertSame($expected, $comment->getLines());
    }

    public function testDocBlockComment(): void
    {
        $commentText = <<<EOD
            /**
             * This is a docblock comment.
             * It has multiple lines.
             *
             * @param string \$param
             * @return void
             */
            EOD;
        $comment = new Comment($commentText);

        $expected = [
            'This is a docblock comment.',
            'It has multiple lines.',
            '@param string $param',
            '@return void',
        ];
        $this->assertSame($expected, $comment->getLines());
    }

    public function testEmptyComment(): void
    {
        $commentText = '/** */';
        $comment = new Comment($commentText);

        $expected = [];
        $this->assertSame($expected, $comment->getLines());
    }

    public function testCommentWithExtraSpaces(): void
    {
        $commentText = <<<EOD
            /*
             *    This comment has
             *    extra spaces.
             */
            EOD;
        $comment = new Comment($commentText);

        $expected = ['This comment has', 'extra spaces.'];
        $this->assertSame($expected, $comment->getLines());
    }
}
