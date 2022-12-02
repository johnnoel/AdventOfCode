<?php

$scores = [ 'X' => 1, 'Y' => 2, 'Z' => 3 ];
$map = [ 'X' => 'rock', 'Y' => 'paper', 'Z' => 'scissors', 'A' => 'rock', 'B' => 'paper', 'C' => 'scissors' ];

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day02.txt'))));
$score = array_map(function (string $l) use ($scores, $map): int {
    [ $opp, $me ] = explode(' ', $l);

    $a = $map[$opp];
    $b = $map[$me];

    if ($a === $b) {
        return (3 + $scores[$me]);
    }

    if (($a === 'rock' && $b === 'paper') || ($a === 'paper' && $b === 'scissors') || ($a === 'scissors' && $b === 'rock')) {
        return (6 + $scores[$me]);
    }

    return $scores[$me];
}, $input);

echo array_sum($score);