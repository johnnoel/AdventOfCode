<?php

$scores = [ 'rock' => 1, 'paper' => 2, 'scissors' => 3 ];
$map = [ 'X' => 'lose', 'Y' => 'draw', 'Z' => 'win', 'A' => 'rock', 'B' => 'paper', 'C' => 'scissors' ];

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day02.txt'))));
$score = array_map(function (string $l) use ($scores, $map): int {
    [ $opp, $me ] = explode(' ', $l);

    $a = $map[$opp];
    $b = $map[$me];

    if ($b === 'draw') {
        return 3 + $scores[$a];
    } elseif ($b === 'win') {
        switch ($a) {
            case 'rock':
                return 6 + $scores['paper'];
            case 'paper':
                return 6 + $scores['scissors'];
            case 'scissors':
                return 6 + $scores['rock'];
        }
    }

    switch ($a) {
        case 'rock':
            return $scores['scissors'];
        case 'paper':
            return $scores['rock'];
        case 'scissors':
            return $scores['paper'];
    }

    return 0;
}, $input);

echo array_sum($score);
