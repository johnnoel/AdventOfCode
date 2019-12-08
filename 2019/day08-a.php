<?php

$input = str_split(trim(file_get_contents(__DIR__.'/day08.txt')));
$cols = 25;
$rows = 6;

$layers = [];

for ($i = 0; $i < count($input); $i += ($cols * $rows)) {
    $layers[] = array_slice($input, $i, ($cols * $rows));
}

$zeroDigitCount = []; // idx = layer, value = count
foreach ($layers as $idx => $layer) {
    $zeroDigitCount[$idx] = count(array_filter($layer, function (string $pixel): bool {
        return $pixel === '0';
    }));
}

asort($zeroDigitCount, SORT_NUMERIC);

$layerWithFewestZeros = array_key_first($zeroDigitCount);

$oneDigitCount = count(array_filter($layers[$layerWithFewestZeros], function (string $pixel): bool {
    return $pixel === '1';
}));

$twoDigitCount = count(array_filter($layers[$layerWithFewestZeros], function (string $pixel): bool {
    return $pixel === '2';
}));

echo $oneDigitCount * $twoDigitCount;
