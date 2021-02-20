<?php

$numbers = array_filter(array_map('intval', explode("\n", file_get_contents(__DIR__ . '/day01.txt'))));

for ($i = 0; $i < count($numbers); $i++) {
    $number1 = $numbers[$i];

    for ($j = $i + 1; $j < count($numbers); $j++) {
        $number2 = $numbers[$j];

        for ($k = $j + 1; $k < count($numbers); $k++) {
            $number3 = $numbers[$k];

            if ($number1 + $number2 + $number3 === 2020) {
                echo $number1 * $number2 * $number3 . PHP_EOL;
                break 3;
            }
        }
    }
}
