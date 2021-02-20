<?php

$input = array_map('intval', explode(',', trim(file_get_contents(__DIR__.'/day05.txt'))));

//$input = [ 3, 0, 4, 0, 99 ];
$actualInput = 1;

for ($i = 0; $i < count($input);) { // no increment
    if ($input[$i] === 99) {
        break;
    }

    $strI = strval($input[$i]);
    $opcode = null;
    $paramModes = [ 0, 0, 0 ];

    if (strlen($strI) === 1) {
        $opcode = $input[$i];
    } else {
        $opcode = intval(substr($strI, -2));
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

    $aVal = ($paramModes[0] === 0) ? $input[$input[$i + 1]] : $input[$i + 1];

    if (in_array($opcode, [ 1, 2 ])) {
        $bVal = ($paramModes[1] === 0) ? $input[$input[$i + 2]] : $input[$i + 2];
        $result = ($opcode === 1) ? $aVal + $bVal : $aVal * $bVal;

        if ($paramModes[2] === 0) {
            $input[$input[$i + 3]] = $result;
        } else {
            $input[$i + 3] = $result;
        }

        $i += 4;
    } elseif (in_array($opcode, [ 3, 4 ])) {
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
    } else {
        throw new RuntimeException('Unknown opcode: '.$opcode.' ['.$i.']');
    }
}
