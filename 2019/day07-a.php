<?php

/**
 * Generate all the different permutations of collection $a
 *
 * @param array $a The collection to permute
 * @param int $k Initially, the number of items in the collection
 * @param array $store Where to store the different permutations
 */
function permutations(array &$a, int $k, array &$store): void
{
    if ($k === 1) {
        $store[] = $a;
    } else {
        for ($i = 0; $i < $k; $i++) {
            permutations($a, $k - 1, $store);

            if (($k % 2) === 0) {
                $tmp = $a[$i];
                $a[$i] = $a[$k - 1];
                $a[$k - 1] = $tmp;
            } else {
                $tmp = $a[0];
                $a[0] = $a[$k - 1];
                $a[$k - 1] = $tmp;
            }
        }
    }
}

/**
 * Run the provided program with provided input
 *
 * @param array $program
 * @param array $input
 * @return array Any output from the program
 */
function runProgram(array $program, array $input = []): array
{
    $output = [];

    for ($i = 0; $i < count($program);) { // no increment
        if ($program[$i] === 99) {
            break;
        }

        $strI = strval($program[$i]);
        $opcode = null;
        $paramModes = [0, 0, 0]; // default parameter modes

        if (strlen($strI) === 1) {
            $opcode = $program[$i];
        } else {
            $opcode = intval(substr($strI, -2));
            // grab the part of the string without the opcode
            // split it into an array of characters
            // reverse that array (params are right to left)
            // turn those characters into integers
            // replace the indexes in $paramModes with the values
            $paramModes = array_replace(
                $paramModes,
                array_map(
                    'intval',
                    array_reverse(
                        str_split(
                            substr(
                                $strI,
                                0,
                                -2
                            )
                        )
                    )
                )
            );
        }

        // all instructions apart from 99 take at least one input
        $aVal = ($paramModes[0] === 0) ? $program[$program[$i + 1]] : $program[$i + 1];

        // perform the different actions for the opcodes
        if (in_array($opcode, [1, 2])) { // sum, mult
            $bVal = ($paramModes[1] === 0) ? $program[$program[$i + 2]] : $program[$i + 2];
            $result = ($opcode === 1) ? $aVal + $bVal : $aVal * $bVal;

            if ($paramModes[2] === 0) {
                $program[$program[$i + 3]] = $result;
            } else {
                $program[$i + 3] = $result;
            }

            $i += 4;
        } elseif (in_array($opcode, [3, 4])) { // set, print
            if ($opcode === 3) {
                $actualInput = array_shift($input);
                if ($paramModes[0] === 0) {
                    $program[$program[$i + 1]] = $actualInput;
                } else {
                    $program[$i + 1] = $actualInput;
                }
            } else {
                if ($paramModes[0] === 0) {
                    $output[] = $program[$program[$i + 1]];
                } else {
                    $output[] = $program[$i + 1];
                }
            }

            $i += 2;
        } elseif (in_array($opcode, [5, 6])) { // jump if true/false
            $bVal = ($paramModes[1] === 0) ? $program[$program[$i + 2]] : $program[$i + 2];

            // the jump action for both opcodes is the same, only the condition is different
            if (($opcode === 5 && $aVal !== 0) || ($opcode === 6 && $aVal === 0)) {
                $i = $bVal;
            } else {
                $i += 3;
            }
        } elseif (in_array($opcode, [7, 8])) { // less than, equals
            $bVal = ($paramModes[1] === 0) ? $program[$program[$i + 2]] : $program[$i + 2];
            $toStore = 0;

            // like jump opcodes, the action is the same but the condition for what to store is different
            if (($opcode === 7 && $aVal < $bVal) || ($opcode === 8 && $aVal === $bVal)) {
                $toStore = 1;
            }

            if ($paramModes[2] === 0) {
                $program[$program[$i + 3]] = $toStore;
            } else {
                $program[$i + 3] = $toStore;
            }

            $i += 4;
        } else {
            throw new RuntimeException('Unknown opcode: ' . $opcode . ' [' . $i . ']');
        }
    }

    return $output;
}

$program = array_map('intval', explode(',', trim(file_get_contents(__DIR__ . '/day07.txt'))));

$phases = [];
$collection = [ 0, 1, 2, 3, 4 ];
permutations($collection, 5, $phases);

$results = [];

foreach ($phases as $phaseSequence) {
    $lastOutput = 0;

    foreach ($phaseSequence as $phase) {
        $cleanProgram = array_merge([], $program);
        $output = runProgram($cleanProgram, [ $phase, $lastOutput ]);
        $lastOutput = $output[0];
    }

    $results[] = [ $phaseSequence, $lastOutput ];
}

usort($results, function (array $a, array $b): int {
    return -1 * ($a[1] <=> $b[1]);
});

foreach ($results as $result) {
    [ $sequence, $thrust ] = $result;
    echo implode(', ', $sequence).' => '.$thrust.PHP_EOL;
}

echo 'Max thruster setting '.$results[0][1].' from sequence '.implode(', ', $results[0][0]);
