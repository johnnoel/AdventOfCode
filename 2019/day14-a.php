<?php

class Reaction
{
    /** @var Reaction */
    public $parent = null;
    /** @var Reaction[] */
    public $children = [];
    /** @var array */
    public $inputs;
    /** @var array */
    public $output;
    /** @var int */
    public $multiplier = 1;

    /**
     * @param array $inputs
     * @param array $output
     */
    public function __construct(array $inputs, array $output)
    {
        $this->inputs = $inputs;
        $this->output = $output;
    }

    /**
     * @param int $multiplier
     */
    public function setMultiplier(int $multiplier): void
    {
        $this->multiplier = $multiplier;
    }
}

/**
 * @param string $name
 * @param array $reactions
 * @return Reaction|null
 */
function findReactionForSubstance(string $name, array $reactions): ?Reaction
{
    /** @var Reaction $reaction */
    foreach ($reactions as $reaction) {
        if ($reaction->output->name === $name) {
            return $reaction;
        }
    }

    return null;
}

$input = explode("\n", trim(file_get_contents(__DIR__.'/day14.txt')));
$input = explode("\n", '157 ORE => 5 NZVS
165 ORE => 6 DCFZ
44 XJWVT, 5 KHKGT, 1 QDVJ, 29 NZVS, 9 GPVTF, 48 HKGWZ => 1 FUEL
12 HKGWZ, 1 GPVTF, 8 PSHF => 9 QDVJ
179 ORE => 7 PSHF
177 ORE => 5 HKGWZ
7 DCFZ, 7 PSHF => 2 XJWVT
165 ORE => 2 GPVTF
3 DCFZ, 7 NZVS, 5 HKGWZ, 10 PSHF => 8 KHKGT');
/** @var Reaction[] $reactions */
$reactions = [];

$oreReactions = [];

foreach ($input as $line) {
    [ $inputs, $output ] = explode('=>', $line, 2);
    [ $outputAmount, $outputName ] = explode(' ', trim($output), 2);
    $inputs = array_map(function (string $input): Substance {
        [ $amount, $name ] = explode(' ', trim($input), 2);

        return new Substance($name, intval($amount));
    }, explode(',', trim($inputs)));

    $output = new Substance($outputName, intval($outputAmount));
    $reaction = new Reaction($inputs, $output);
    $reactions[] = $reaction;

    if (count($inputs) === 1 && $inputs[0]->name === 'ORE') {
        $oreReactions[] = $reaction;
    }
}

// tree of reactions
// children are possible reactions with multiplier (e.g. reaction A x 1, reaction A x 2)
// children are ordered by how many children they have (or multiplier) and how many resources they use

$path = [];

for ($branchingFactor = 1; $branchingFactor <= 20; $branchingFactor++) {
    $root = clone reset($oreReactions);

    $queue = new SplPriorityQueue();
    $queue->insert($root, 1);

    while (!$queue->isEmpty()) {
        $reaction = $queue->extract();
    }
}
