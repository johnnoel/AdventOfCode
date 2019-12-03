<?php

$wires = array_map(function (string $wire): array {
    return explode(',', trim($wire));
}, explode("\n", trim(file_get_contents(__DIR__.'/day03.txt'))));

$wireLines = [];

foreach ($wires as $k => $wire) {
    $position = [ 0, 0 ];

    foreach ($wire as $instruction) {
        $direction = substr($instruction, 0, 1);
        $amount = intval(substr($instruction, 1));
        $key = 0; // x
        $modifier = 1; // positive

        if ($direction === 'U') {
            $key = 1;
        } elseif ($direction === 'D') {
            $key = 1;
            $modifier = -1;
        } elseif ($direction === 'L') {
            $modifier = -1;
        }

        $newPosition = array_merge([], $position);
        $newPosition[$key] += ($amount * $modifier);

        $wireLines[$k][] = [ $position, $newPosition ];
        $position = $newPosition;
    }
}

// intersect
// see: https://stackoverflow.com/a/1968345
function intersect(array $l1, array $l2): ?array
{
    [ [ $x1, $y1 ], [ $x2, $y2 ] ] = $l1;
    [ [ $x3, $y3 ], [ $x4, $y4 ] ] = $l2;

    $s1x = $x2 - $x1;
    $s1y = $y2 - $y1;
    $s2x = $x4 - $x3;
    $s2y = $y4 - $y3;

    $sDiv = (-$s2x * $s1y + $s1x * $s2y);
    $tDiv = (-$s2x * $s1y + $s1x * $s2y);

    if ($sDiv === 0 || $tDiv === 0) {
        return null;
    }

    $s = (-$s1y * ($x1 - $x3) + $s1x * ($y1 - $y3)) / $sDiv;
    $t = ($s2x * ($y1 - $y3) - $s2y * ($x1 - $x3)) / $tDiv;

    if ($s >= 0 && $s <= 1 && $t >= 0 && $t <= 1) {
        return [
            $x1 + ($t * $s1x),
            $y1 + ($t * $s1y),
        ];
    }

    return null;
}

function manhattan(array $p1, array $p2): int
{
    [ $x1, $y1 ] = $p1;
    [ $x2, $y2 ] = $p2;

    return abs($x1 - $x2) + abs($y1 - $y2);
}

$intersectionPoints = [];

for ($i = 0; $i < count($wireLines[0]); $i++) {
    $wireLineA = $wireLines[0][$i];

    for ($j = 0; $j < count($wireLines[1]); $j++) {
        $wireLineB = $wireLines[1][$j];
        $intersectPoint = intersect($wireLineA, $wireLineB);

        if ($intersectPoint !== null) {
            $intersectPoint[2] = manhattan($intersectPoint, [ 0, 0 ]);

            echo sprintf(
                '%d,%d -> %d,%d | %d,%d -> %d,%d | %d,%d [%d]'.PHP_EOL,
                $wireLineA[0][0], $wireLineA[0][1], $wireLineA[1][0], $wireLineA[1][1],
                $wireLineB[0][0], $wireLineB[0][1], $wireLineB[1][0], $wireLineB[1][1],
                $intersectPoint[0], $intersectPoint[1],
                $intersectPoint[2]
            );

            $intersectionPoints[] = $intersectPoint;
        }
    }
}

usort($intersectionPoints, function (array $a, array $b): int {
    return $a[2] <=> $b[2];
});

echo $intersectionPoints[0][0].','.$intersectionPoints[0][1].' -> '.$intersectionPoints[0][2].PHP_EOL;