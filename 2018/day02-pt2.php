<?php

$ids = array_filter(explode("\n", file_get_contents(__DIR__.'/inputs/02.txt')));
$idCount = count($ids);

for ($i = 0; $i < $idCount; $i++) {
    $left = str_split($ids[$i]);

    for ($j = ($i + 1); $j < $idCount; $j++) {
        $right = str_split($ids[$j]);
        $diffs = [];

        foreach ($left as $k => $leftLetter) {
            $rightLetter = $right[$k];

            if ($leftLetter !== $rightLetter) {
                $diffs[] = $k;

                if (count($diffs) > 1) {
                    break;
                }
            }
        }

        if (count($diffs) === 1) {
            $common = $left;
            unset($common[$diffs[0]]);
            $common = implode('', $common);

            echo sprintf('Key 1: %s, Key 2: %s, Common: %s'.PHP_EOL, $ids[$i], $ids[$j], $common);
            break 2;
        }
    }
}
