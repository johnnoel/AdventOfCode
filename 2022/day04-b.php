<?php

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day04.txt'))));

$overlaps = array_reduce($input, function (int $carry, string $l): int {
    [ $a, $b ] = explode(',', $l);
    [ $aStart, $aEnd ] = array_map('intval', explode('-', $a));
    [ $bStart, $bEnd ] = array_map('intval', explode('-', $b));

    $aRange = range($aStart, $aEnd);
    $bRange = range($bStart, $bEnd);

    if (count(array_intersect($aRange, $bRange)) > 0) {
        return $carry + 1;
    }

    return $carry + 0;
}, 0);

echo $overlaps;
