<?php

$fh = fopen(__DIR__ . '/day01.txt', 'r');
$list1 = [];
$list2 = [];

while (!feof($fh)) {
    $line = fgets($fh);
    if (trim($line) == '') {
        continue;
    }

    [ $one, $two ] = array_map('intval', array_map('trim', explode('   ', $line)));
    $list1[] = $one;
    $list2[] = $two;
}

sort($list1, SORT_NUMERIC);
sort($list2, SORT_NUMERIC);

$distances = [];
foreach ($list1 as $k => $v1) {
    $v2 = $list2[$k];
    $distances[] = max($v1, $v2) - min($v1, $v2);
}

echo array_sum($distances) . PHP_EOL;
