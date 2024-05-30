#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
use DouglasGreen\CodeSmith\CssRenderer;
use DouglasGreen\CodeSmith\Lexer;
use DouglasGreen\CodeSmith\Parser;
use DouglasGreen\OptParser\OptParser;

$optParser = new OptParser('CodeSmith', 'Test program');

$optParser->addFlag(['verbose', 'v'], 'Verbose output')
    ->addUsageAll();

$input = $optParser->parse();

$isVerbose = (bool) $input->get('verbose');

// Example usage:
$sampleCss = <<<CSS
    /* ID Selectors */
    #header {
        background-color: #4CAF50;
        color: white;
        text-align: center;
        padding: 10px 0;
    }

    #main-content {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
    }

    #footer {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 10px 0;
    }

    /* Class Selectors */
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #008CBA;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #005f73;
    }

    /* Media Queries */

    /* For devices with a width of up to 600px */
    @media only screen and (max-width: 600px) {
        #header, #footer {
            text-align: left;
            padding: 20px;
        }
        .button {
            display: block;
            width: 100%;
            padding: 15px;
            text-align: center;
        }
    }

    /* For devices with a width between 601px and 768px */
    @media only screen and (min-width: 601px) and (max-width: 768px) {
        #main-content {
            margin: 10px;
            padding: 15px;
        }
        .container {
            padding: 0 10px;
        }
    }

    /* For devices with a width of more than 768px */
    @media only screen and (min-width: 769px) {
        .container {
            padding: 0 20px;
        }
        #main-content {
            padding: 25px;
            border-width: 2px;
        }
    }
    CSS;

$input = <<<TXT
    /* ID Selectors */
    id header {
        background_color #4caf50;
        color white;
        text_align center;
        padding 10px 0;
    }
    TXT;

$lexer = new Lexer($input, $isVerbose);
$parser = new Parser($lexer, $isVerbose);
$syntaxTree = $parser->parse();

if ($isVerbose) {
    echo json_encode($syntaxTree, JSON_PRETTY_PRINT);
}

//$linter = new CssLinter($syntaxTree);
//$issues = $linter->lint();

$renderer = new CssRenderer($syntaxTree);
$html = $renderer->render();
