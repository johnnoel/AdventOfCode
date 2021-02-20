<?php

$rawInput = explode(PHP_EOL, trim(file_get_contents(__DIR__.'/inputs/12.txt')));
$rawInitial = str_split(substr($rawInput[0], 15));
$rawRules = array_slice($rawInput, 2);

class Pot {
    /** @var bool */
    public $hasPlant = false;
    /** @var int */
    public $idx;

    public function __construct(bool $hasPlant, int $idx)
    {
        $this->hasPlant = $hasPlant;
        $this->idx = $idx;
    }
}

class Rule {
    /** @var array<bool> */
    public $rule;
    /** @var bool */
    public $result;

    public function __construct(array $rule, bool $result)
    {
        $this->rule = $rule;
        $this->result = $result;
    }

    /**
     * @param array<Pot> $pots
     */
    public function matches(array $pots)
    {
        for ($i = 0; $i < 5; $i++) {
            if ($pots[$i]->hasPlant !== $this->rule[$i]) {
                return false;
            }
        }

        return true;
    }
}

function countPots(int $carry, Pot $p) : int
{
    return $carry += ($p->hasPlant) ? $p->idx : 0;
}

$initialState = [];
$rules = [];

for ($i = -3; $i < (count($rawInitial) + 3); $i++) {
    $pot = null;
    if (array_key_exists($i, $rawInitial)) {
        $pot = new Pot(($rawInitial[$i] === '#'), $i);
    } else {
        $pot = new Pot(false, $i);
    }

    $initialState[] = $pot;
}

foreach ($rawRules as $rawRule) {
    $splitRule = str_split($rawRule);
    $rule = array_map(function($s) {
        return ($s === '#');
    }, array_slice($splitRule, 0, 5));
    $result = ($splitRule[9] === '#');

    $rules[] = new Rule($rule, $result);
}

function printState(array $state) : string
{
    $ret = '';
    foreach ($state as $pot) {
        $ret .= ($pot->hasPlant) ? '#' : '.';
    }

    return $ret;
}

$generations = 50000000000;
//$generations = 20;
$state = $initialState;
$ratesOfChange = array_fill(0, 10, 0);

echo '00 '.printState($state).PHP_EOL;

for ($generation = 1; $generation <= $generations; $generation++) {
    $nextState = [];

    for ($i = 0; $i < count($state); $i++) {
        $pot = $state[$i];
        $pots = [];

        // create a 5 pot state
        for ($j = -2; $j <= 2; $j++) {
            if (($i + $j) < 0 || ($i + $j) >= count($state)) {
                $pots[] = new Pot(false, $pot->idx + $j);
            } else {
                $pots[] = $state[$i + $j];
            }
        }

        $nextPot = new Pot(false, $pot->idx);

        foreach ($rules as $rule) {
            if ($rule->matches($pots)) {
                $nextPot = new Pot($rule->result, $pot->idx);
                break;
            }
        }

        $nextState[] = $nextPot;
    }

    $lastThree = array_slice($nextState, -3);
    foreach ($lastThree as $pot) {
        if ($pot->hasPlant) {
            $nextState[] = new Pot(false, $nextState[count($nextState) - 1]->idx + 1);
        }
    }

    // trim state
    for ($i = 0; $i < count($nextState); $i++) {
        if ($nextState[$i+3]->hasPlant) {
            break;
        }

        unset($nextState[$i]);
    }

    $nextState = array_merge($nextState);

    if ($generation > 1) {
        $nextStateVal = array_reduce($nextState, 'countPots', 0);
        $lastStateVal = array_reduce($state, 'countPots', 0);

        $roc = ($nextStateVal - $lastStateVal);

        array_push($ratesOfChange, $roc);
        $ratesOfChange = array_slice($ratesOfChange, 1);

        // has the rate of change stabilised?
        if (count(array_unique($ratesOfChange, SORT_NUMERIC)) === 1) {
            $toGo = $generations - $generation;
            $toAdd = $toGo * $roc;

            var_dump($nextStateVal + $toAdd);

            exit;
        }
    }

    $state = $nextState;
}

echo PHP_EOL.array_reduce($state, 'countPots', 0).PHP_EOL;
