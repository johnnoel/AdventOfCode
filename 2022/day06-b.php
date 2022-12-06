<?php

$fh = fopen(__DIR__ . '/day06.txt', 'r');
$charBuffer = [];
$bufferSize = 14;

for ($i = 1; !feof($fh); $i++) {
    $charBuffer[] = fgetc($fh);

    if (count($charBuffer) < $bufferSize) {
        continue;
    } else if (count($charBuffer) > $bufferSize) {
        $charBuffer = array_slice($charBuffer, 0 - $bufferSize);
    }

    if (count(array_unique($charBuffer)) === $bufferSize) {
        echo $i;
        break;
    }
}
