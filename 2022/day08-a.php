<?php

$rowsAndCols = array_map(fn (string $r): array => str_split($r), array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day08.txt')))));

// initial number of visible trees
$visibleTrees = (count($rowsAndCols) * 2) + ((count($rowsAndCols[0]) - 2) * 2);

$xMax = count($rowsAndCols[0]) - 2;
$yMax = count($rowsAndCols) - 2;

for ($x = 1; $x <= $xMax; $x++) {
    $row = $rowsAndCols[$x];

    for ($y = 1; $y <= $yMax; $y++) {
        $tree = $rowsAndCols[$x][$y];
        $col = array_column($rowsAndCols, $y);

        // left
        $canSee = true;
        for ($i = 0; $i < $y; $i++) {
            if ($row[$i] >= $tree) {
                $canSee = false;
                break;
            }
        }

        if ($canSee) {
            //echo sprintf('L (%d, %d) %d' . PHP_EOL, $x, $y, $tree);
            $visibleTrees++;
            continue;
        }

        // right
        $canSee = true;
        for ($i = $xMax + 1; $i > $y; $i--) {
            if ($row[$i] >= $tree) {
                $canSee = false;
                break;
            }
        }

        if ($canSee) {
            //echo sprintf('R (%d, %d) %d' . PHP_EOL, $x, $y, $tree);
            $visibleTrees++;
            continue;
        }

        // top
        $canSee = true;
        for ($i = 0; $i < $x; $i++) {
            if ($col[$i] >= $tree) {
                $canSee = false;
                break;
            }
        }

        if ($canSee) {
            //echo sprintf('T (%d, %d) %d' . PHP_EOL, $x, $y, $tree);
            $visibleTrees++;
            continue;
        }

        // bottom
        $canSee = true;
        for ($i = $yMax + 1; $i > $x; $i--) {
            if ($col[$i] >= $tree) {
                $canSee = false;
                break;
            }
        }

        if ($canSee) {
            //echo sprintf('B (%d, %d) %d' . PHP_EOL, $x, $y, $tree);
            $visibleTrees++;
        }
    }
}

echo $visibleTrees . PHP_EOL;