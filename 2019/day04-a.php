<?php

[ $minRange, $maxRange ] = array_map('intval', explode('-', trim(file_get_contents(__DIR__.'/day04.txt'))));

function isValid(int $number): bool
{
    $numberStr = strval($number);
    if (strlen($numberStr) !== 6) {
        return false;
    }

    $hasDouble = false;
    $lastNumber = null;
    for ($idx = 0; $idx < strlen($numberStr); $idx++) {
        $number = substr($numberStr, $idx, 1);

        if ($lastNumber !== null) {
            if (intval($number) < intval($lastNumber)) {
                return false;
            }

            if ($number === $lastNumber) {
                $hasDouble = true;
            }
        }

        $lastNumber = $number;
    }

    return $hasDouble;
}

$validCount = 0;

for ($i = $minRange; $i <= $maxRange; $i++) {
    $valid = isValid($i);

    if (isValid($i)) {
        $validCount++;
    }

    //echo sprintf('%d - %s'.PHP_EOL, $i, ($valid) ? 'true' : 'false');
}

echo 'Valid passcodes: '.$validCount.PHP_EOL;
