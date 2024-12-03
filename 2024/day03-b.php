<?php

$input = trim(file_get_contents(__DIR__ . '/day03.txt'));

$instructions = [];
preg_match_all('/(do\(\)|don\'t\(\)|mul\(\d{1,3},\d{1,3}\))/', $input, $instructions);

$results = [];
$on = true;

foreach ($instructions[0] as $instruction) {
    if ($instruction == 'don\'t()') {
        $on = false;
    } else if ($instruction == 'do()') {
        $on = true;
    } else if ($on) {
        [ $one, $two ] = array_map('intval', explode(',', substr($instruction, 4, -1)));
        $results[] = $one * $two;
    }
}

echo array_sum($results) . PHP_EOL;