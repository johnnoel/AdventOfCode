<?php

$polymer = trim(file_get_contents(__DIR__.'/inputs/05.txt'));
$newPolymer = $polymer;

$blockSize = 100;

function reduceBlock(array $block) : array
{
    //echo implode($block).PHP_EOL;
    $newBlock = [];

    for ($j = 0; $j < count($block) - 1; $j++) {
        $a = $block[$j];
        $b = $block[$j + 1];

        //echo '  Comparing '.$a.' vs '.$b.PHP_EOL;

        if ($a !== $b && strtolower($a) === strtolower($b)) {
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

do {
    $polymer = $newPolymer;
    /*$blockCount = ceil(strlen($polymer) / $blockSize);
    $blocks = [];

    for ($i = 0; $i < $blockCount; $i++) {
        $block = str_split(substr($polymer, ($i * $blockSize), $blockSize));
        $newBlock = reduceBlock($block);

        while (count($newBlock) < count($block)) {
            $block = $newBlock;
            $newBlock = reduceBlock($block);
        }

        $blocks[] = $block;
    }*/

    $block = str_split($polymer);
    $newBlock = reduceBlock($block);

    while (count($newBlock) < count($block)) {
        $block = $newBlock;
        $newBlock = reduceBlock($block);
    }

    $newPolymer = array_reduce($block, function($acc, $b) {
        return $acc . ((is_string($b)) ? $b: implode($b));
    }, '');
} while (strlen($newPolymer) < strlen($polymer));

var_dump(strlen($polymer));
