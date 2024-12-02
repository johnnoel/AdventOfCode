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

$similarities = [];

foreach ($list1 as $v1) {
    $o = array_filter($list2, fn($v2): bool => $v2 === $v1);
    $similarities[] = $v1 * count($o);
}

echo array_sum($similarities) . PHP_EOL;