<?php

declare(strict_types=1);

namespace DouglasGreen\CodeSmith;

use DouglasGreen\Utility\Regex\Regex;

class Comment
{
    /**
     * @var list<string>
     */
    protected array $lines;

    public function __construct(
        protected string $comment
    ) {
        $this->parseComment();
    }

    /**
     * @return list<string>
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    protected function parseComment(): void
    {
        // Split into lines by any line break
        $lines = Regex::split('/\R/', $this->comment);

        // Process each line
        $processedLines = array_map(static function ($line): string {
            // Remove trailing */ characters
            $line = Regex::replace('#\*/\s*$#', '', $line);

            // Remove leading /*, /**, and * characters
            $line = Regex::replace('#^\s*(/\*\*?|\*)#', '', $line);

            return trim($line);
        }, $lines);

        // Filter out empty lines
        $this->lines = array_values(
            array_filter($processedLines, static fn($line): bool => $line !== ''),
        );
    }
}
