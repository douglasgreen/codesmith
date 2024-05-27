#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DouglasGreen\OptParser\OptParser;
use DouglasGreen\Syntax\Lexer;
use DouglasGreen\Syntax\Parser;

$optParser = new OptParser('Simple Universal Syntax', 'Test program');

$optParser->addFlag(['verbose', 'v'], 'Verbose output')
    ->addUsageAll();

$input = $optParser->parse();

$isVerbose = (bool) $input->get('verbose');

// Example usage:
$input = 'select * from Customers; /* Comment */ word "string" 123 { nested; } (word 123) [key: value];';
$lexer = new Lexer($input, $isVerbose);

$parser = new Parser($lexer, $isVerbose);
$ast = $parser->parse();

if ($isVerbose) {
    echo json_encode($ast, JSON_PRETTY_PRINT);
}
