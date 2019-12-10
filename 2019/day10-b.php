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
                echo '.';
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

//$coords = [ 31, 20 ];
$coords = [ 8, 3 ];

$lines = explode("\n", trim(file_get_contents(__DIR__.'/day10.txt')));
$lines = explode("\n",
'.#....#####...#..
##...##.#####..##
##...#...#.#####.
..#.....#...###..
..#.#.....#....##');

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

while (!empty($availableAsteroids)) {
    $visible = getVisible($coords[0], $coords[1], $availableAsteroids);
    var_dump($visible);
    break;
}
