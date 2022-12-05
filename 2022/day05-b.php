<?php

[ $start, $moves ] = explode("\n\n", trim(file_get_contents(__DIR__ . '/day05.txt')));

//
// parse the starting stacks
//
$stacks = [];
$lines = array_reverse(explode("\n", $start));
array_shift($lines); // get rid of the 1 -> 9

foreach ($lines as $line) {
    $crates = str_split($line, 4);

    foreach ($crates as $k => $crate) {
        $crate = substr($crate, 1, 1);
        if (trim($crate) === '') {
            continue;
        }

        $stacks[$k][] = $crate;
    }
}

//
// perform the moves
//
$moves = explode("\n", $moves);
foreach ($moves as $move) {
    [ $xx, $count, $xy, $src, $xz, $target ] = explode(' ', $move);

    $crates = array_splice($stacks[intval($src) - 1], 0 - intval($count));
    $stacks[intval($target) - 1] = array_merge($stacks[intval($target - 1)], $crates);
}

//var_dump($stacks);

echo implode(array_map(function (array $stack): string {
    return end($stack);
}, $stacks));
