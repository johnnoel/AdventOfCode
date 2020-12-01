<?php

$numbers = array_filter(array_map('intval', explode("\n", file_get_contents(__DIR__ . '/day01.txt'))));

for ($i = 0; $i < count($numbers); $i++) {
    $number1 = $numbers[$i];

    for ($j = $i + 1; $j < count($numbers); $j++) {
        $number2 = $numbers[$j];

        if ($number1 + $number2 === 2020) {
            echo $number1 * $number2 . PHP_EOL;
        }
    }
}