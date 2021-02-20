<?php

$input = array_map('intval', explode(',', trim(file_get_contents(__DIR__ . '/day05.txt'))));

$actualInput = 5;

for ($i = 0; $i < count($input);) { // no increment
    if ($input[$i] === 99) {
        break;
    }

    $strI = strval($input[$i]);
    $opcode = null;
    $paramModes = [0, 0, 0]; // default parameter modes

    if (strlen($strI) === 1) {
        $opcode = $input[$i];
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
    $aVal = ($paramModes[0] === 0) ? $input[$input[$i + 1]] : $input[$i + 1];

    // perform the different actions for the opcodes
    if (in_array($opcode, [1, 2])) { // sum, mult
        $bVal = ($paramModes[1] === 0) ? $input[$input[$i + 2]] : $input[$i + 2];
        $result = ($opcode === 1) ? $aVal + $bVal : $aVal * $bVal;

        if ($paramModes[2] === 0) {
            $input[$input[$i + 3]] = $result;
        } else {
            $input[$i + 3] = $result;
        }

        $i += 4;
    } elseif (in_array($opcode, [3, 4])) { // set, print
        if ($opcode === 3) {
            if ($paramModes[0] === 0) {
                $input[$input[$i + 1]] = $actualInput;
            } else {
                $input[$i + 1] = $actualInput;
            }
        } else {
            if ($paramModes[0] === 0) {
                echo $input[$input[$i + 1]];
            } else {
                echo $input[$i + 1];
            }
        }

        $i += 2;
    } elseif (in_array($opcode, [5, 6])) { // jump if true/false
        $bVal = ($paramModes[1] === 0) ? $input[$input[$i + 2]] : $input[$i + 2];

        // the jump action for both opcodes is the same, only the condition is different
        if (($opcode === 5 && $aVal !== 0) || ($opcode === 6 && $aVal === 0)) {
            $i = $bVal;
        } else {
            $i += 3;
        }
    } elseif (in_array($opcode, [7, 8])) { // less than, equals
        $bVal = ($paramModes[1] === 0) ? $input[$input[$i + 2]] : $input[$i + 2];
        $toStore = 0;

        // like jump opcodes, the action is the same but the condition for what to store is different
        if (($opcode === 7 && $aVal < $bVal) || ($opcode === 8 && $aVal === $bVal)) {
            $toStore = 1;
        }

        if ($paramModes[2] === 0) {
            $input[$input[$i + 3]] = $toStore;
        } else {
            $input[$i + 3] = $toStore;
        }

        $i += 4;
    } else {
        throw new RuntimeException('Unknown opcode: ' . $opcode . ' [' . $i . ']');
    }
}
