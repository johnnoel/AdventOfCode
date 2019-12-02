<?php

$originalInput = array_map('intval', explode(',', trim(file_get_contents(__DIR__.'/day02.txt'))));
$target = 19690720;

for ($noun = 0; $noun <= 99; $noun++) {
    for ($verb = 0; $verb <= 99; $verb++) {
        $input = array_merge([], $originalInput);
        $input[1] = $noun;
        $input[2] = $verb;

        for ($i = 0; $i < count($input);) { // no increment
            if ($input[$i] === 99) {
                break;
            }

            $aPos = $input[$i + 1];
            $bPos = $input[$i + 2];
            $storePos = $input[$i + 3];

            $a = $input[$aPos];
            $b = $input[$bPos];

            if ($input[$i] === 1) { // sum
                $input[$storePos] = $a + $b;
            } else if ($input[$i] === 2) { // mult
                $input[$storePos] = $a * $b;
            }

            $i += 4;
        }

        if ($input[0] === $target) {
            echo 'Noun: '.$noun.PHP_EOL;
            echo 'Verb: '.$verb.PHP_EOL;
            break 2;
        }
    }
}

echo 'Done'.PHP_EOL;
