<?php

$fh = fopen(__DIR__ . '/day06.txt', 'r');
$charBuffer = [];

for ($i = 1; !feof($fh); $i++) {
    $charBuffer[] = fgetc($fh);

    if (count($charBuffer) < 4) {
        continue;
    } else if (count($charBuffer) > 4) {
        $charBuffer = array_slice($charBuffer, -4);
    }

    if (count(array_unique($charBuffer)) === 4) {
        echo $i;
        break;
    }
}