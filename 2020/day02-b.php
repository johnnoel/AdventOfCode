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

    $pos1 = intval($matches[1]);
    $pos2 = intval($matches[2]);
    $toMatch = $matches[3];
    $password = $matches[4];

    $chars = str_split($password);
    $char1 = $chars[$pos1 - 1];
    $char2 = $chars[$pos2 - 1];

    $valid += (($char1 === $toMatch && $char2 !== $toMatch) || ($char1 !== $toMatch && $char2 === $toMatch)) ? 1 : 0;
}

echo $valid . PHP_EOL;
