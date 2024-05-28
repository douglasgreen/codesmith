<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax\Tests;

use DouglasGreen\Syntax\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testSingleLineComment()
    {
        $commentText = '/* This is a single line comment */';
        $comment = new Comment($commentText);
        
        $expected = ['This is a single line comment'];
        $this->assertEquals($expected, $comment->getLines());
    }

    public function testMultiLineComment()
    {
        $commentText = <<<EOD
/*
 * This is a
 * multiline comment
 */
EOD;
        $comment = new Comment($commentText);
        
        $expected = ['This is a', 'multiline comment'];
        $this->assertEquals($expected, $comment->getLines());
    }

    public function testDocBlockComment()
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
            '@return void'
        ];
        $this->assertEquals($expected, $comment->getLines());
    }

    public function testEmptyComment()
    {
        $commentText = '/** */';
        $comment = new Comment($commentText);
        
        $expected = [];
        $this->assertEquals($expected, $comment->getLines());
    }

    public function testCommentWithExtraSpaces()
    {
        $commentText = <<<EOD
/*
 *    This comment has
 *    extra spaces.
 */
EOD;
        $comment = new Comment($commentText);
        
        $expected = ['This comment has', 'extra spaces.'];
        $this->assertEquals($expected, $comment->getLines());
    }
}

