<?php

$input = array_map('intval', explode(',', trim(file_get_contents(__DIR__.'/day02.txt'))));
// replacements
$input[1] = 12;
$input[2] = 2;

for ($i = 0; $i < count($input);) { // no increment
    if ($input[$i] === 99) {
        break;
    }

    $aPos = $input[$i + 1];
    $bPos = $input[$i + 2];
    $storePos = $input[$i + 3];

    $a = $input[$aPos];
    $b = $input[$bPos];

    if ($input[$i] === 1) { // sum
        $input[$storePos] = $a + $b;
    } else if ($input[$i] === 2) { // mult
        $input[$storePos] = $a * $b;
    }

    $i += 4;
}

echo $input[0].PHP_EOL;
