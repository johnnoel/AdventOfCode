<?php

[$minRange, $maxRange] = array_map('intval', explode('-', trim(file_get_contents(__DIR__ . '/day04.txt'))));

function isValid(int $number): bool
{
    $numberStr = strval($number);
    if (strlen($numberStr) !== 6) {
        return false;
    }

    $numberGroups = []; // key = number, value = count
    $lastNumber = null;
    for ($idx = 0; $idx < strlen($numberStr); $idx++) {
        $number = substr($numberStr, $idx, 1);

        if ($lastNumber !== null) {
            if (intval($number) < intval($lastNumber)) {
                return false;
            }

            if ($number === $lastNumber) {
                if (!array_key_exists($number, $numberGroups)) {
                    $numberGroups[$number] = 1;
                }

                $numberGroups[$number]++;
            }
        }

        $lastNumber = $number;
    }

    if (empty($numberGroups)) {
        return false;
    }

    $twoLengthNumberGroups = array_filter($numberGroups, function (int $numberGroupLength): bool {
        return $numberGroupLength === 2;
    });

    return count($twoLengthNumberGroups) >= 1;
}

$validCount = 0;

for ($i = $minRange; $i <= $maxRange; $i++) {
    $valid = isValid($i);

    if (isValid($i)) {
        $validCount++;
    }

    //echo sprintf('%d - %s'.PHP_EOL, $i, ($valid) ? 'true' : 'false');
}

echo 'Valid passcodes: ' . $validCount . PHP_EOL;
