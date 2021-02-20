<?php

// see https://stackoverflow.com/a/328122
function isBetween(int $ax, int $ay, int $bx, int $by, int $cx, int $cy): bool
{
    $crossProduct = ($cy - $ay) * ($bx - $ax) - ($cx - $ax) * ($by - $ay);

    if (abs($crossProduct) !== 0) {
        return false;
    }

    $dotProduct = ($cx - $ax) * ($bx - $ax) + ($cy - $ay) * ($by - $ay);

    if ($dotProduct < 0) {
        return false;
    }

    $squaredLengthBA = ($bx - $ax) * ($bx - $ax) + ($by - $ay) * ($by - $ay);
    if ($dotProduct > $squaredLengthBA) {
        return false;
    }

    return true;
}

/**
 * Get all of the visible asteroids from point $ax, $ay
 *
 * @param int $ax
 * @param int $ay
 * @param array $asteroids
 * @return array
 */
function getVisible(int $ax, int $ay, array $asteroids): array
{
    $visible = [];

    // can a see b?
    for ($matchB = 0; $matchB < count($asteroids); $matchB++) {
        $b = $asteroids[$matchB];
        [ $bx, $by ] = $b;

        $canSee = true;

        // go through the asteroids again and figure out if they lie on the line between A and B
        for ($matchC = 0; $matchC < count($asteroids); $matchC++) {
            if ($matchC === $matchB) {
                continue;
            }

            $c = $asteroids[$matchC];
            [ $cx, $cy ] = $c;

            if (isBetween($ax, $ay, $bx, $by, $cx, $cy)) {
                $canSee = false;
                break;
            }
        }

        if ($canSee) {
            $visible[] = [ $bx, $by ];
        }
    }

    return $visible;
}

$coords = [ 31, 20 ];

$lines = explode("\n", trim(file_get_contents(__DIR__.'/day10.txt')));

$grid = array_map(function (string $line): array {
    return str_split(trim($line));
}, $lines);

// grid coords of each asteroid
$asteroids = [];
foreach ($grid as $y => $line) {
    foreach ($line as $x => $cell) {
        if ($cell === '#') {
            $asteroids[] = [ $x, $y ];
        }
    }
}

$availableAsteroids = array_merge(array_filter($asteroids, function (array $asteroid) use ($coords): bool {
    return !($asteroid[0] === $coords[0] && $asteroid[1] === $coords[1]);
}));

$vaporised = 0;

while (!empty($availableAsteroids)) {
    //echo count($availableAsteroids);
    $visible = getVisible($coords[0], $coords[1], $availableAsteroids);
    $angles = [];

    foreach ($visible as $k => [ $x, $y ]) {
        $angle = atan2($x - $coords[0], $y - $coords[1]);
        $angles[$k] = $angle;
    }

    arsort($angles, SORT_NUMERIC);

    foreach ($angles as $k => $angle) {
        $x = $visible[$k][0];
        $y = $visible[$k][1];
        echo sprintf('[%d] %d,%d => %f', ++$vaporised, $x, $y, $angle).PHP_EOL;
    }

    $availableAsteroids = array_merge(array_filter($availableAsteroids, function (array $xy) use ($visible): bool {
        return !in_array($xy, $visible);
    }));

    //echo count($availableAsteroids);
}
