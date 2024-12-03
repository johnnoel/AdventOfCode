<?php

$input = trim(file_get_contents(__DIR__ . '/day03.txt'));

$instructions = [];
preg_match_all('/mul\((\d{1,3},\d{1,3})\)/', $input, $instructions);

$results = [];

foreach ($instructions[1] as $instruction) {
    [ $one, $two ] = array_map('intval', explode(',', $instruction));
    $results[] = $one * $two;
}

echo array_sum($results) . PHP_EOL;