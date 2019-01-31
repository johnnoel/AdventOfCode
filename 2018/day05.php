<?php

$fullPolymer = trim(file_get_contents(__DIR__.'/inputs/05.txt'));

function reduceBlock(array $block, string $forceRemove) : array
{
    //echo implode($block).PHP_EOL;
    $newBlock = [];

    for ($j = 0; $j < count($block) - 1; $j++) {
        $a = $block[$j];
        $b = $block[$j + 1];

        //echo '  Comparing '.$a.' vs '.$b.PHP_EOL;

        if (strtolower($a) === strtolower($forceRemove)) {
            //echo '  Force removing'.PHP_EOL;
            continue;
        } else if ($a !== $b && strtolower($a) === strtolower($b)) {
            //echo '  Removing'.PHP_EOL;
            $j++;
        } else {
            $newBlock[] = $a;
            //echo '  Adding '.$a.' to new block ('.implode($newBlock).')'.PHP_EOL;
        }
    }

    $newBlock[] = $block[count($block) - 1];
    //echo implode($newBlock).PHP_EOL.PHP_EOL;

    return $newBlock;
}

$letters = range('a', 'z');
$letterToLength = array_combine($letters, array_fill(0, 26, 0));

foreach ($letters as $letter) {
    $polymer = $fullPolymer;
    $newPolymer = $fullPolymer;

    do {
        $polymer = $newPolymer;

        $block = str_split($polymer);
        $newBlock = reduceBlock($block, $letter);

        while (count($newBlock) < count($block)) {
            $block = $newBlock;
            $newBlock = reduceBlock($block, $letter);
        }

        $newPolymer = array_reduce($block, function($acc, $b) {
            return $acc . ((is_string($b)) ? $b: implode($b));
        }, '');
    } while (strlen($newPolymer) < strlen($polymer));

    $letterToLength[$letter] = strlen($polymer);
    echo $letter.' - '.strlen($polymer).PHP_EOL;
}

asort($letterToLength, SORT_NUMERIC);
reset($letterToLength);

echo key($letterToLength).' - '.current($letterToLength);
