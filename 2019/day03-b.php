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
function intersect(int $x1, int $y1, int $x2, int $y2, int $x3, int $y3, int $x4, int $y4): ?array
{
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

$intersectionPoints = [];
$stepsA = 0;

for ($i = 0; $i < count($wireLines[0]); $i++) {
    //$wireLineA = $wireLines[0][$i];
    [ [ $ax1, $ay1 ], [ $ax2, $ay2 ]] = $wireLines[0][$i];
    $stepsA += abs($ax2 - $ax1) + abs($ay2 - $ay1);
    $stepsB = 0;

    for ($j = 0; $j < count($wireLines[1]); $j++) {
        //$wireLineB = $wireLines[1][$j];
        [ [ $bx1, $by1 ], [ $bx2, $by2 ] ] = $wireLines[1][$j];
        $stepsB += abs($bx2 - $bx1) + abs($by2 - $by1);
        $intersectPoint = intersect($ax1, $ay1, $ax2, $ay2, $bx1, $by1, $bx2, $by2);

        if ($intersectPoint !== null) {
            [ $ix, $iy ] = $intersectPoint;
            $stepsAIntersect = $stepsA - (abs($ax2 - $ix) + abs($ay2 - $iy));
            $stepsBIntersect = $stepsB - (abs($bx2 - $ix) + abs($by2 - $iy));
            $intersectPoint[2] = $stepsAIntersect + $stepsBIntersect;

            echo sprintf(
                '%d,%d -> %d,%d | %d,%d -> %d,%d | %d,%d [%d / %d => %d]'.PHP_EOL,
                $ax1, $ay1, $ax2, $ay2, $bx1, $by1, $bx2, $by2,
                $intersectPoint[0], $intersectPoint[1],
                $stepsAIntersect, $stepsBIntersect, $intersectPoint[2]
            );

            $intersectionPoints[] = $intersectPoint;
        }
    }
}

usort($intersectionPoints, function (array $a, array $b): int {
    return $a[2] <=> $b[2];
});

$winner = ($intersectionPoints[0][2] === 0) ? $intersectionPoints[1] : $intersectionPoints[0];

echo $winner[0].','.$winner[1].' -> '.$winner[2].PHP_EOL;
