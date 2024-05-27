<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax;

class Statement
{
    public string $type = 'statement';

    /**
     * @param array<Expression> $expressions
     */
    public function __construct(
        public ?Token $comment,
        public Token $token,
        public array $expressions,
        public ?Block $block
    ) {}
}
