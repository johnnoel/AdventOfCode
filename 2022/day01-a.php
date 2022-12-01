<?php

$elves = explode("\n\n", trim(file_get_contents(__DIR__ . '/day01.txt')));
$elfCalories = array_map(function (string $e): int {
    return array_sum(array_map('intval', explode("\n", $e)));
}, $elves);

rsort($elfCalories);

echo $elfCalories[0];
