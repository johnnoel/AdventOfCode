<?php

$fh = fopen(__DIR__.'/inputs/02.txt', 'r');

$twos = 0;
$threes = 0;

while (!feof($fh)) {
    $line = trim(fgets($fh));
    if (empty($line)) {
        continue;
    }

    $letters = [];
    $split = str_split($line);

    foreach ($split as $letter) {
        if (!array_key_exists($letter, $letters)) {
            $letters[$letter] = 0;
        }

        $letters[$letter]++;
    }

    // DEBUG
    $sortedKeys = array_keys($letters);
    sort($sortedKeys);

    echo sprintf('%s => [ ', $line);
    foreach ($sortedKeys as $k) {
        echo sprintf('%s => %d, ', $k, $letters[$k]);
    }
    echo ' ]'.PHP_EOL;
    // DEBUG

    $twos += (in_array(2, $letters)) ? 1 : 0;
    $threes += (in_array(3, $letters)) ? 1 : 0;
}

echo sprintf('2s: %d, 3s: %d, Checksum: %d'.PHP_EOL, $twos, $threes, ($twos * $threes));
