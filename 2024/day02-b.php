<?php

$isSafe = function (array $reports): bool {
    $safe = true;
    $inc = ($reports[0] < $reports[1]);

    for ($i = 1; $i < count($reports); $i++) {
        $one = $reports[$i - 1];
        $two = $reports[$i];
        $diff = abs($one - $two);

        if (($inc && $one > $two) || (!$inc && $one < $two) || $diff < 1 || $diff > 3) {
            $safe = false;
            break;
        }
    }

    return $safe;
};


$fh = fopen(__DIR__ . '/day02.txt', 'r');
$safeCount = 0;

while (!feof($fh)) {
    $line = fgets($fh);
    if (trim($line) == '') {
        continue;
    }

    $reports = array_map('intval', explode(' ', $line));
    $safe = $isSafe($reports);

    if (!$safe) {
        // start removing levels to see if it becomes safe
        for ($lvl = 0; $lvl < count($reports); $lvl++) {
            $newReports = array_slice($reports, 0);
            array_splice($newReports, $lvl, 1);

            if ($isSafe($newReports)) {
                $safe = true;
                break;
            }
        }
    }

    echo implode(' ', $reports) . ($safe ? ' yes' : ' no') . PHP_EOL;

    if ($safe) {
        $safeCount++;
    }

    //echo implode(' ', $reports) . ($safe ? ' yes' : ' no') . PHP_EOL;
}

echo $safeCount . PHP_EOL;
