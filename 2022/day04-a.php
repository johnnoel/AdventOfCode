<?php

$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day04.txt'))));

$overlaps = array_reduce($input, function (int $carry, string $l): int {
    [ $a, $b ] = explode(',', $l);
    [ $aStart, $aEnd ] = array_map('intval', explode('-', $a));
    [ $bStart, $bEnd ] = array_map('intval', explode('-', $b));

    // devtodo use min/max here to simplify this check
    if (($aStart >= $bStart && $aEnd <= $bEnd) || ($bStart >= $aStart && $bEnd <= $aEnd)) {
        return $carry + 1;
    }

    return $carry + 0;
}, 0);

echo $overlaps;