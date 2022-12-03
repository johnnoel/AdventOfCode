<?php

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day03.txt'))));
$scores = array_flip(array_merge(range('a', 'z'), range('A', 'Z')));

$score = array_reduce($input, function (int $carry, string $r) use ($scores): int {
    [ $a, $b ] = array_map('array_unique', array_map('str_split', str_split($r, strlen($r) / 2)));
    $intersect = array_intersect($a, $b);

    return $carry + $scores[reset($intersect)] + 1;
}, 0);

echo $score;