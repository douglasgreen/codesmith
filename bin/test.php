#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DouglasGreen\CodeSmith\Lexer;
use DouglasGreen\CodeSmith\Parser;
use DouglasGreen\OptParser\OptParser;

$optParser = new OptParser('CodeSmith', 'Test program');

$optParser->addFlag(['verbose', 'v'], 'Verbose output')
    ->addUsageAll();

$input = $optParser->parse();

$isVerbose = (bool) $input->get('verbose');

// Example usage:
$input = 'select * from Customers; /* Comment */ word "string" 123 { nested; } other (word 123) [key: value];';
$lexer = new Lexer($input, $isVerbose);

$parser = new Parser($lexer, $isVerbose);
$syntaxTree = $parser->parse();

if ($isVerbose) {
    echo json_encode($syntaxTree, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
}
