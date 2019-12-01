<?php

$input = explode("\n", trim(file_get_contents(__DIR__.'/day01.txt')));

$fuelRequired = array_reduce($input, function (int $carry, string $mass): int {
    $initialFuel = (floor(intval($mass) / 3) - 2);
    $fuelWeights = [ $initialFuel ];

    while (true) {
        $lastFuel = end($fuelWeights);
        $extraFuel = (floor($lastFuel / 3) - 2);
        if ($extraFuel <= 0) {
            break;
        }

        $fuelWeights[] = $extraFuel;
    }

    $requiredFuel = array_sum($fuelWeights);
    echo sprintf('Input: %s, result: %d, total so far: %d'.PHP_EOL, $mass, $requiredFuel, $carry);

    return $carry + $requiredFuel;
}, 0);

echo $fuelRequired.PHP_EOL;

