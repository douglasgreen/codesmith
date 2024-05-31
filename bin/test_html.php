#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DouglasGreen\CodeSmith\HtmlLinter;
use DouglasGreen\CodeSmith\HtmlRenderer;
use DouglasGreen\CodeSmith\Lexer;
use DouglasGreen\CodeSmith\Parser;
use DouglasGreen\OptParser\OptParser;

$optParser = new OptParser('CodeSmith', 'Test program');

$optParser->addFlag(['verbose', 'v'], 'Verbose output')
    ->addUsageAll();

$input = $optParser->parse();

$isVerbose = (bool) $input->get('verbose');

// Example usage:
$sampleHtml = <<<HTML_WRAP
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A sample HTML file using different tags and attributes">
        <title>Sample HTML Document</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Welcome to My Website</h1>
            <nav>
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </nav>
        </header>
        <main>
            <article>
                <section id="home">
                    <h2>Home</h2>
                    <p>Welcome to my website. Here you will find a variety of information about me and the services I offer.</p>
                    <img src="image.jpg" alt="A descriptive image" width="600" height="400">
                </section>
                <section id="about">
                    <h2>About</h2>
                    <p>This section contains information about me. I am a web developer with a passion for creating interactive and dynamic websites.</p>
                    <blockquote cite="https://example.com">
                        "This is a sample quote from an external source."
                    </blockquote>
                </section>
                <section id="services">
                    <h2>Services</h2>
                    <p>Here are some of the services I offer:</p>
                    <ul>
                        <li>Web Development</li>
                        <li>Graphic Design</li>
                        <li>SEO Optimization</li>
                    </ul>
                    <table>
                        <caption>Service Prices</caption>
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Web Development</td>
                                <td>\$1000</td>
                            </tr>
                            <tr>
                                <td>Graphic Design</td>
                                <td>\$500</td>
                            </tr>
                            <tr>
                                <td>SEO Optimization</td>
                                <td>\$300</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <section id="contact">
                    <h2>Contact</h2>
                    <p>If you would like to get in touch, please fill out the form below:</p>
                    <form action="/submit" method="post">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        <br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                        <br>
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required></textarea>
                        <br>
                        <button type="submit">Submit</button>
                    </form>
                </section>
            </article>
        </main>
        <footer>
            <p>&copy; 2024 My Website. All rights reserved.</p>
        </footer>
    </body>
    </html>
    HTML_WRAP;

$input = <<<TXT
    doctype html;
    html [lang: en] {
        head {
            meta [charset: "UTF-8"];
            meta [name: viewport content: "width: device-width, initial-scale: 1.0"];
            meta [name: description content: "A sample HTML file using different tags and attributes"];
            title "Sample HTML Document";
            link [rel: stylesheet href: "styles.css"];
        }
    }
    TXT;

$lexer = new Lexer($input, $isVerbose);
$parser = new Parser($lexer, $isVerbose);
$syntaxTree = $parser->parse();

if ($isVerbose) {
    echo json_encode($syntaxTree, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
}

$linter = new HtmlLinter($syntaxTree);
$issues = $linter->lint();

$renderer = new HtmlRenderer($syntaxTree);
$html = $renderer->render();
