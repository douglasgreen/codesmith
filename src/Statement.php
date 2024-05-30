<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

class Statement
{
    /**
     * @param array<Expression> $expressions
     */
    public function __construct(
        public ?Token $comment,
        public Token $word,
        public array $expressions,
        public ?Block $block
    ) {}
}
