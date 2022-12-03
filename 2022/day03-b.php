<?php

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day03.txt'))));
$scores = array_flip(array_merge(range('a', 'z'), range('A', 'Z')));
$groups = [];

for ($i = 0; $i < count($input); $i+=3) {
    $groups[] = array_slice($input, $i, 3);
}

$score = array_reduce($groups, function (int $carry, array $group) use ($scores): int {
    $g = array_map('str_split', $group);
    $intersection = array_intersect(...$g);

    return $carry + $scores[reset($intersection)] + 1;
}, 0);

echo $score;