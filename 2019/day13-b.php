<?php

class Intputer
{
    /** @var int[] */
    private $program;
    /** @var int[] */
    private $originalProgram;
    /** @var int[] */
    private $input;
    /** @var int */
    private $pointer;
    /** @var int */
    private $base;

    /**
     * @param int[] $program
     * @param int[] $input
     */
    public function __construct(array $program, array $input = [])
    {
        $this->originalProgram = $program;
        $this->program = $program;
        $this->input = $input;
        $this->pointer = 0;
        $this->base = 0;
    }

    /**
     * Reset the intputer to its original program and memory state
     */
    public function reset(): void
    {
        $this->program = array_merge([], $this->originalProgram);
        $this->pointer = 0;
        $this->base = 0;
        $this->input = [];
    }

    /**
     * Run the intputer program from wherever it last stopped
     *
     * @param array $input
     * @return int|null
     */
    public function run(array $input = []): ?int
    {
        $this->input = array_merge($this->input, $input);

        while ($this->pointer < count($this->program)) {
            if ($this->program[$this->pointer] === 99) {
                return null;
            }

            $strI = strval($this->program[$this->pointer]);
            $opcode = null;
            $paramModes = [0, 0, 0]; // default parameter modes

            if (strlen($strI) === 1) {
                $opcode = $this->program[$this->pointer];
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
            $aVal = $this->getParameter(1, $paramModes[0]);

            // perform the different actions for the opcodes
            if (in_array($opcode, [1, 2])) { // sum, mult
                $bVal = $this->getParameter(2, $paramModes[1]);
                $result = ($opcode === 1) ? $aVal + $bVal : $aVal * $bVal;
                $this->setParameter(3, $paramModes[2], $result);
                $this->pointer += 4;
            } elseif ($opcode === 3) { // set
                $actualInput = array_shift($this->input);
                $this->setParameter(1, $paramModes[0], $actualInput);
                $this->pointer += 2;
            } elseif ($opcode === 4) { // print
                $this->pointer += 2;
                return $aVal;
            } elseif (in_array($opcode, [5, 6])) { // jump if true/false
                $bVal = $this->getParameter(2, $paramModes[1]);

                // the jump action for both opcodes is the same, only the condition is different
                if (($opcode === 5 && $aVal !== 0) || ($opcode === 6 && $aVal === 0)) {
                    $this->pointer = $bVal;
                } else {
                    $this->pointer += 3;
                }
            } elseif (in_array($opcode, [7, 8])) { // less than, equals
                $bVal = $this->getParameter(2, $paramModes[1]);
                $toStore = 0;

                // like jump opcodes, the action is the same but the condition for what to store is different
                if (($opcode === 7 && $aVal < $bVal) || ($opcode === 8 && $aVal === $bVal)) {
                    $toStore = 1;
                }

                $this->setParameter(3, $paramModes[2], $toStore);
                $this->pointer += 4;
            } elseif ($opcode === 9) {
                $this->base += $aVal;
                $this->pointer += 2;
            } else {
                throw new RuntimeException('Unknown opcode: ' . $opcode . ' [' . $this->pointer . ']');
            }
        }

        return null;
    }

    private function getParameter(int $pointerOffset, int $mode): ?int
    {
        // todo do this better, kind of janky now
        $offset = $this->pointer + $pointerOffset;
        if (!array_key_exists($offset, $this->program)) {
            $this->program[$offset] = 0;
        }

        if (!array_key_exists($this->program[$offset], $this->program)) {
            $this->program[$this->program[$offset]] = 0;
        }

        switch ($mode) {
            case 0: // position
                return $this->program[$this->program[$offset]];
            case 1: // value
                return $this->program[$offset];
            case 2: // relative position
                return $this->program[$this->program[$offset] + $this->base];
        }

        throw new RuntimeException('Bad parameter mode');
    }

    private function setParameter(int $pointerOffset, int $mode, int $value): void
    {
        $offset = $this->pointer + $pointerOffset;
        if (!array_key_exists($offset, $this->program)) {
            $this->program[$offset] = 0;
        }

        if (!array_key_exists($this->program[$offset], $this->program)) {
            $this->program[$this->program[$offset]] = 0;
        }

        switch ($mode) {
            case 0: // position
                $this->program[$this->program[$offset]] = $value;
                break;
            case 1: // value
                $this->program[$offset] = $value;
                break;
            case 2: // relative position
                $this->program[$this->program[$offset] + $this->base] = $value;
                break;
        }
    }
}

function showScreen(array $screen) {
    for ($y = 0; $y < count($screen); $y++) {
        $line = [];
        for ($x = 0; $x < count($screen[$y]); $x++) {
            switch ($screen[$y][$x]) {
                case 0:
                    $line[] = ' '; // nothing
                    break;
                case 1:
                    $line[] = '+'; // wall
                    break;
                case 2:
                    $line[] = '#'; // block
                    break;
                case 3:
                    $line[] = '_'; // horizontal paddle
                    break;
                case 4:
                    $line[] = 'o'; // ball
                    break;
            }
        }

        echo implode('', $line).PHP_EOL;
    }
    echo PHP_EOL;
}

$program = array_map('intval', explode(',', trim(file_get_contents(__DIR__.'/day13.txt'))));
$program[0] = 2; // free play
$intputer = new Intputer($program, [ 0, 0, 0, 0 ]);

$screen = [];
$output = null;
$input = [];
$score = 0;
$drawn = 0;

do {
    $x = $intputer->run();
    $y = $intputer->run();
    $tile = $intputer->run();

    if ($x === -1 && $y === 0) {
        $score = $tile;
    } else {
        $screen[$y][$x] = $tile;
    }

    if (count($screen, true) >= 840) {
        showScreen($screen);
        echo $score.PHP_EOL;
        echo $drawn++.PHP_EOL;
    }

    $output = $tile;

    //echo sprintf('%dx%d, %d'.PHP_EOL, $x, $y, $tile);
} while ($output !== null);

