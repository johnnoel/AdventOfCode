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

// which grid points are owned by what coord
$gridOwnership = [];

for ($y = 0; $y <= $maxY; $y++) {
    for ($x = 0; $x <= $maxX; $x++) {
        $pointDistances = [];

        foreach ($coords as $k => $point) {
            $distance = manhattanDistance([ $x, $y ], $point);

            if (!array_key_exists($distance, $pointDistances)) {
                $pointDistances[$distance] = [];
            }

            $pointDistances[$distance][] = $k;
        }

        $minDist = min(array_keys($pointDistances));
        if (count($pointDistances[$minDist]) > 1) {
            continue;
        }

        $gridOwnership[$x][$y] = $pointDistances[$minDist][0];
    }
}

$areas = array_fill(0, 50, 0);
$infiniteAreas = [];

for ($y = 0; $y <= $maxY; $y++) {
    for ($x = 0; $x <= $maxX; $x++) {

        if (isset($gridOwnership[$x][$y])) {
            $coordKey = $gridOwnership[$x][$y];
            $areas[$coordKey]++;

            if ($x === 0 || $y === 0 || $x === $maxX || $y === $maxY) {
                $infiniteAreas[] = $coordKey;
            }
        }
    }
}

$infiniteAreas = array_unique($infiniteAreas);

arsort($areas, SORT_NUMERIC);

foreach ($areas as $coordKey => $area) {
    if (!in_array($coordKey, $infiniteAreas)) {
        var_dump($coords[$coordKey], $area);
        break;
    }
}
