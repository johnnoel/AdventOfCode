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

// which steps have we seen, but don't have any pre-requisites to start?
$availableSteps = array_diff(range('A', 'Z'), array_keys($map));
$performed = [];

while (true) {
    sort($availableSteps);
    $performed[] = array_shift($availableSteps);

    foreach ($map as $step => $preReqs) {
        // if we've already done it or it's already possible
        if (in_array($step, $performed) || in_array($step, $availableSteps)) {
            continue;
        }

        // if we don't match the prerequisites for performing the step
        foreach ($preReqs as $preReq) {
            if (!in_array($preReq, $performed)) {
                continue 2;
            }
        }

        $availableSteps[] = $step;
    }

    if (count($availableSteps) === 0) {
        if (count($performed) === 26) {
            echo 'Success: '.implode(' -> ', $performed).PHP_EOL;
            echo implode($performed).PHP_EOL;
            break;
        } else {
            echo 'Dead end: '.implode(' -> ', $performed).PHP_EOL;
            break;
        }
    }
}
