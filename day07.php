<?php

$instructions = array_filter(explode("\n", file_get_contents(__DIR__.'/inputs/07.txt')));
$regex = '/^Step ([A-Z]) must be finished before step ([A-Z]) can begin.$/';

// key = step, value = steps that must be completed before key can start
$map = array_combine(range('A', 'Z'), array_fill(0, 26, []));

foreach ($instructions as $inst) {
    $matches = [];
    $matched = preg_match($regex, $inst, $matches);

    if ($matched !== 1) {
        continue;
    }

    $mustFinish = $matches[1];
    $step = $matches[2];

    $map[$step][] = $mustFinish;
}

// key = second, value = [ steps ]
$workLog = [];
// key = step, value = time spent
$currentWork = [];
// value = step
$availableSteps = [];
$performed = [];
// key = step, value = time cost
$stepTimes = array_combine(range('A', 'Z'), range(61, 86));

$workers = 5;

for ($second = 0; ; $second++) {
    // filter out any steps that have completed
    foreach ($currentWork as $step => &$timeSpent) {
        if ($timeSpent === $stepTimes[$step]) {
            unset($currentWork[$step]);
            $performed[] = $step;
            continue;
        }

        $timeSpent++;
    }

    // get the steps available to perform
    foreach ($map as $step => $preReqs) {
        // if we've already done it, or it's already possible, or working on it
        if (in_array($step, $performed) || in_array($step, $availableSteps) || array_key_exists($step, $currentWork)) {
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

    sort($availableSteps);

    // all workers occupied
    if (count($currentWork) === $workers) {
        continue;
    }

    while (count($currentWork) < $workers) {
        if (count($availableSteps) > 0) {
            $currentWork[array_shift($availableSteps)] = 1;
        } else {
            break;
        }
    }

    $workLog[$second] = $currentWork;

    echo sprintf('%d - %s', $second, implode(', ', array_keys($currentWork))).PHP_EOL;

    if (count($availableSteps) === 0 && count($currentWork) === 0) {
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
