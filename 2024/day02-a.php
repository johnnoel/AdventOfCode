<?php

$fh = fopen(__DIR__ . '/day02.txt', 'r');
$safeCount = 0;

while (!feof($fh)) {
    $line = fgets($fh);
    if (trim($line) == '') {
        continue;
    }

    $reports = array_map('intval', explode(' ', $line));
    $safe = true;
    $inc = ($reports[0] < $reports[1]);

    for ($i = 1; $i < count($reports); $i++) {
        $one = $reports[$i - 1];
        $two = $reports[$i];

        if ($inc && $one > $two || !$inc && $one < $two) {
            $safe = false;
            break;
        }

        $diff = abs($one - $two);

        if ($diff < 1 || $diff > 3) {
            $safe = false;
            break;
        }
    }

    if ($safe) {
        $safeCount++;
    }
}

echo $safeCount . PHP_EOL;