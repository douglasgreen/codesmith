<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

use DouglasGreen\Exceptions\ValueException;

class CssRenderer extends Renderer
{
    /**
     * @throws ValueException
     */
    public function render(): string
    {
        foreach ($this->syntaxTree->statements as $statement) {
            $comment = $statement->comment;
            if ($comment !== null) {
                echo $comment->value . PHP_EOL;
            }

            $word = $statement->word;
            switch ($word->value) {
                case 'id':
                    echo '#';
                    break;
                case 'class':
                    echo '.';
                    break;
                default:
                    throw new ValueException('Invalid word: ' . $word->value);
            }

            foreach ($statement->expressions as $expression) {
                $expr = $expression->expression;
                if ($expr instanceof Token) {
                    echo $expr->value;
                } else {
                    throw new ValueException(
                        'Invalid expression: ' . $expr::class,
                    );
                }
            }
        }

        return '';
    }
}
