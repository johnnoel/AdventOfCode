<?php

$fh = fopen(__DIR__.'/inputs/01.txt', 'r');
$running = 0;

while (!feof($fh)) {
    $line = trim(fgets($fh));
    if (empty($line)) {
        continue;
    }

    $sign = substr($line, 0, 1);
    $mod = ($sign === '-') ? -1 : 1;

    $number = intval(substr($line, 1));

    $newRunning = $running + ($number * $mod);

    echo sprintf('%d + %s => %d'.PHP_EOL, $running, $line, $newRunning);
    $running = $newRunning;
}
