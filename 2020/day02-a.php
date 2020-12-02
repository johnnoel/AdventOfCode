<?php

$lines = explode("\n", trim(file_get_contents(__DIR__ . '/day02.txt')));
$valid = 0;

foreach ($lines as $line) {
    $regex = '/^(\d+)-(\d+) ([a-z]): (.*)$/';
    $matches = [];

    $matched = preg_match($regex, $line, $matches);

    if ($matched !== 1) {
        echo 'Couldnt match ' . $line . PHP_EOL;
        continue;
    }

    $min = intval($matches[1]);
    $max = intval($matches[2]);
    $toMatch = $matches[3];
    $password = $matches[4];

    $chars = str_split($password);
    $matchedChars = count(array_filter($chars, fn ($char) => $char === $toMatch));

    $valid += ($matchedChars >= $min && $matchedChars <= $max) ? 1 : 0;
}

echo $valid . PHP_EOL;