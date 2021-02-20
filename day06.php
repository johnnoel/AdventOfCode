<?php

function manhattanDistance(array $xy1, array $xy2) : int
{
    return abs($xy1[0] - $xy2[0]) + abs($xy1[1] - $xy2[1]);
}

$coords = array_filter(array_map(function(string $line) {
    return (empty($line)) ? null : array_map('intval', explode(',', $line));
}, explode("\n", file_get_contents(__DIR__.'/inputs/06.txt'))));

$maxX = array_reduce($coords, function($acc, $item) {
    return max($acc, $item[0]);
}, 0);

$maxY = array_reduce($coords, function($acc, $item) {
    return max($acc, $item[1]);
}, 0);

$tenKOrLess = [];

for ($y = 0; $y <= $maxY; $y++) {
    for ($x = 0; $x <= $maxX; $x++) {
        $totalDistance = array_reduce($coords, function($acc, $item) use ($x, $y) {
            return $acc + manhattanDistance([ $x, $y ], $item);
        }, 0);

        if ($totalDistance < 10000) {
            $tenKOrLess[] = [ $x, $y ];
        }
    }
}

var_dump(count($tenKOrLess));
