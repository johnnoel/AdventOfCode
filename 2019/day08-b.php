<?php

$input = str_split(trim(file_get_contents(__DIR__.'/day08.txt')));
$cols = 25;
$rows = 6;

$layers = [];

for ($i = 0; $i < count($input); $i += ($cols * $rows)) {
    $layers[] = array_slice($input, $i, ($cols * $rows));
}

$layerCount = count($layers);
$image = []; // x, y

// 0 = black, 1 = white, 2 = transparent

for ($row = 0; $row < $rows; $row++) {
    for ($col = 0; $col < $cols; $col++) {
        for ($layer = ($layerCount - 1); $layer >= 0; $layer--) {
            $offset = ($row * $cols) + $col;
            //echo sprintf('%d x %d => %d (%d)'.PHP_EOL, $col, $row, $offset, $layer);
            //sleep(1);
            $pixel = $layers[$layer][$offset];

            if ($pixel === '2') { // do nothing, whatever was there before
                continue;
            } else {
                $image[$col][$row] = $pixel;
            }
        }
    }
}

for ($row = 0; $row < $rows; $row++) {
    for ($col = 0; $col < $cols; $col++) {
        echo ($image[$col][$row] === '0') ? ' ' : '+';
    }
    echo PHP_EOL;
}
