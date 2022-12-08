<?php

$rowsAndCols = array_map(fn(string $r): array => str_split($r), array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day08.txt')))));
$bestScenicScore = 0;

$h = count($rowsAndCols);
$w = count($rowsAndCols[0]);

for ($x = 1; $x < ($w - 1); $x++) {
    for ($y = 1; $y < ($h - 1); $y++) {
        $tree = $rowsAndCols[$y][$x];
        $col = array_column($rowsAndCols, $x);

        $l = array_reverse(array_slice($rowsAndCols[$y], 0, $x));
        $r = array_slice($rowsAndCols[$y], $x + 1);
        $u = array_reverse(array_slice($col, 0, $y));
        $d = array_slice($col, $y + 1);

        $scores = array_fill(0, 4, 0);

        foreach ([ $u, $l, $r, $d ] as $k => $i) {
            foreach ($i as $t) {
                $scores[$k]++;

                if ($t >= $tree) {
                    break;
                }
            }
        }

        $score = array_reduce($scores, fn (int $carry, int $score): int => $carry * $score, 1);
        //echo sprintf('(%d, %d) %d' . PHP_EOL, $x, $y, $score);
        $bestScenicScore = ($score > $bestScenicScore) ? $score : $bestScenicScore;
    }
}

echo $bestScenicScore . PHP_EOL;