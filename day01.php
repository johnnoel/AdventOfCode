<?php

$fh = fopen(__DIR__.'/inputs/01.txt', 'r');
$running = 0;

$seen = [];

while (true) {
    $line = trim(fgets($fh));
    if (empty($line)) {
        if (feof($fh)) {
            fseek($fh, 0);
            echo '.';
        }

        continue;
    }

    $sign = substr($line, 0, 1);
    $mod = ($sign === '-') ? -1 : 1;

    $number = intval(substr($line, 1));

    $newRunning = $running + ($number * $mod);

    if (in_array($newRunning, $seen)) {
        echo sprintf('%d + %s => %d !! DOUBLE'.PHP_EOL, $running, $line, $newRunning);
        break;
    } else {
        $seen[] = $newRunning;
    }

    //echo sprintf('%d + %s => %d'.PHP_EOL, $running, $line, $newRunning);
    $running = $newRunning;
}

fclose($fh);
