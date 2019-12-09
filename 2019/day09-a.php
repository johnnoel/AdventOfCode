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
            //$aVal = ($paramModes[0] === 0) ? $this->program[$this->program[$this->pointer + 1]] : $this->program[$this->pointer + 1];

            // perform the different actions for the opcodes
            if (in_array($opcode, [1, 2])) { // sum, mult
                $bVal = $this->getParameter(2, $paramModes[1]);
                //$bVal = ($paramModes[1] === 0) ? $this->program[$this->program[$this->pointer + 2]] : $this->program[$this->pointer + 2];
                $result = ($opcode === 1) ? $aVal + $bVal : $aVal * $bVal;

                if ($paramModes[2] === 0) {
                    $this->program[$this->program[$this->pointer + 3]] = $result;
                } else {
                    $this->program[$this->pointer + 3] = $result;
                }

                $this->pointer += 4;
            } elseif ($opcode === 3) { // set
                $actualInput = array_shift($this->input);
                if ($paramModes[0] === 0) {
                    $this->program[$this->program[$this->pointer + 1]] = $actualInput;
                } else {
                    $this->program[$this->pointer + 1] = $actualInput;
                }

                $this->pointer += 2;
            } elseif ($opcode === 4) { // print
                if ($paramModes[0] === 0) {
                    $ret = intval($this->program[$this->program[$this->pointer + 1]]);
                } else {
                    $ret = intval($this->program[$this->pointer + 1]);
                }

                $this->pointer += 2;
                return $aVal;
            } elseif (in_array($opcode, [5, 6])) { // jump if true/false
                $bVal = $this->getParameter(2, $paramModes[1]);
                //$bVal = ($paramModes[1] === 0) ? $this->program[$this->program[$this->pointer + 2]] : $this->program[$this->pointer + 2];

                // the jump action for both opcodes is the same, only the condition is different
                if (($opcode === 5 && $aVal !== 0) || ($opcode === 6 && $aVal === 0)) {
                    $this->pointer = $bVal;
                } else {
                    $this->pointer += 3;
                }
            } elseif (in_array($opcode, [7, 8])) { // less than, equals
                $bVal = $this->getParameter(2, $paramModes[1]);
                //$bVal = ($paramModes[1] === 0) ? $this->program[$this->program[$this->pointer + 2]] : $this->program[$this->pointer + 2];
                $toStore = 0;

                // like jump opcodes, the action is the same but the condition for what to store is different
                if (($opcode === 7 && $aVal < $bVal) || ($opcode === 8 && $aVal === $bVal)) {
                    $toStore = 1;
                }

                if ($paramModes[2] === 0) {
                    $this->program[$this->program[$this->pointer + 3]] = $toStore;
                } else {
                    $this->program[$this->pointer + 3] = $toStore;
                }

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
}

$program = array_map('intval', explode(',', trim(file_get_contents(__DIR__.'/day09.txt'))));

$intputer = new Intputer($program, [ 1 ]);
$output = [];
do {
    $lastOutput = $intputer->run();
    $output[] = $lastOutput;
} while ($lastOutput !== null);

echo implode('', $output).PHP_EOL;
