<?php

$instructions = array_filter(explode("\n", file_get_contents(__DIR__.'/inputs/07.txt')));
$regex = '/^Step ([A-Z]) must be finished before step ([A-Z]) can begin.$/';

// key = step, value = steps that must be completed before key can start
$map = [];

foreach ($instructions as $inst) {
    $matches = [];
    $matched = preg_match($regex, $inst, $matches);

    if ($matched !== 1) {
        continue;
    }

    $mustFinish = $matches[1];
    $step = $matches[2];

    if (!array_key_exists($step, $map)) {
        $map[$step] = [];
    }

    $map[$step][] = $mustFinish;
}

$seenSteps = array_unique(array_reduce($map, function($acc, $steps) {
    return array_merge($acc, $steps);
}, []));

// which steps have we seen, but don't have any pre-requisites to start?
$startingSteps = array_diff($seenSteps, array_keys($map));
sort($startingSteps);

$performed = $startingSteps;

while (true) {
    // which steps have we not performed?
    $availableSteps = array_diff(array_keys($map), $performed);

    if (count($availableSteps) === 0) {
        echo 'Exhausted available steps with path: '.implode(' -> ', $performed).PHP_EOL;
        echo implode($performed).PHP_EOL;
        break;
    }

    $nextSteps = [];

    foreach ($availableSteps as $availableStep) {
        // have we performed all the necessary steps to perform the available one?
        foreach ($map[$availableStep] as $neededStep) {
            if (!in_array($neededStep, $performed)) {
                continue 2;
            }
        }

        $nextSteps[] = $availableStep;
    }

    if (count($nextSteps) === 0) {
        echo 'Dead end with path: '.implode(' -> ', $performed).PHP_EOL;
        break;
    }

    sort($nextSteps);
    $performed[] = reset($nextSteps);
}

/*$allAfters = array_reduce($afterMap, function($acc, $item) {
    return array_merge($acc, $item);
}, []);

$startingPoints = array_diff(range('A', 'Z'), $allAfters);

foreach ($startingPoints as $startingInstruction) {
    $performed = [ $startingInstruction ];

    while (count($performed) < 26) {
        $lastInstruction = end($performed);
        $possibleNextSteps = $afterMap[$lastInstruction];

        if (empty($possibleNextSteps)) {
            echo 'Dead end for journey '.implode(' -> ', $performed).PHP_EOL;
            break;
        }

        sort($possibleNextSteps);
        $nextStep = reset($possibleNextSteps);

        $performed[] = $nextStep;
        //var_dump('------------------');

        $notPerformed = array_diff(range('A', 'Z'), $performed);
        $possibleNextSteps = array_diff($afterMap[end($performed)], $notPerformed);

        if ($possibleNextSteps === null) {
        }

        sort($possibleNextSteps);
        $performed[] = reset($possibleNextSteps);*/

        /*$notPerformed = array_diff(array_keys($afterMap), $performed);
        $possibleNextSteps = [];

        foreach ($notPerformed as $step) {
            $diff = array_diff($afterMap[$step], $performed);
            if (count($diff) === 0) {
                $possibleNextSteps[] = $step;
            }
        }

        if (count($possibleNextSteps) === 0) {
        }

        sort($possibleNextSteps);
        $performed[] = reset($possibleNextSteps);
    }

    //break;

    if (count($performed) === 26) {
        var_dump(implode($performed));
    }
}*/
