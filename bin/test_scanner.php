<?php

declare(strict_types=1);

use DouglasGreen\CodeSmith\Scanner;

require_once __DIR__ . '/../vendor/autoload.php';

$sample = file_get_contents($argv[0]);
if ($sample === false) {
    die('Bad file' . PHP_EOL);
}

$scanner = new Scanner($sample);

$scanner->scan();
