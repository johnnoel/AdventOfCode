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

$visible = [];

// for every asteroid, need to figure out how many other asteroids it can see
for ($idx = 0; $idx < count($asteroids); $idx++) {
    $a = $asteroids[$idx]; // how many are visible from here
    [ $ax, $ay ] = $a;

    // can a see b?
    for ($matchB = 0; $matchB < count($asteroids); $matchB++) {
        if ($idx === $matchB) { // don't match with itself
            continue;
        }

        $b = $asteroids[$matchB];
        [ $bx, $by ] = $b;
        $distanceAB = sqrt(pow(2, $bx - $ax) + pow(2, $by - $ay));
        $canSee = true;

        // go through the asteroids again and figure out if they lie on the line between A and B
        for ($matchC = 0; $matchC < count($asteroids); $matchC++) {
            if ($matchC === $matchB || $matchC === $idx) {
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
            if (!array_key_exists($idx, $visible)) {
                $visible[$idx] = 0;
            }

            $visible[$idx]++;
        }
    }
}

arsort($visible, SORT_NUMERIC);
$best = $asteroids[array_key_first($visible)];
echo sprintf('%d,%d => %d', $best[0], $best[1], $visible[array_key_first($visible)]).PHP_EOL;
