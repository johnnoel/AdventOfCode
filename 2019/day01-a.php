<?php

$input = explode("\n", trim(file_get_contents(__DIR__.'/day01.txt')));
$fuelRequired = array_reduce($input, function (int $carry, string $mass): int {
    $moduleFuel = (floor(intval($mass) / 3) - 2);
    echo sprintf('Input: %s, result: %d, total so far: %d'.PHP_EOL, $mass, $moduleFuel, $carry);

    return $carry + $moduleFuel;
}, 0);

echo $fuelRequired.PHP_EOL;

