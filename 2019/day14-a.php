<?php

class Reaction
{
    /** @var Substance[] */
    public $inputs;
    /** @var Substance */
    public $output;

    /**
     * @param array $inputs
     * @param Substance $output
     */
    public function __construct(array $inputs, Substance $output)
    {
        $this->inputs = $inputs;
        $this->output = $output;
    }
}

class Substance
{
    /** @var string */
    public $name;
    /** @var int */
    public $amount;

    /**
     * @param string $name
     * @param int $amount
     */
    public function __construct(string $name, int $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
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
$input = explode("\n", '2 VPVL, 7 FWMGM, 2 CXFTF, 11 MNCFX => 1 STKFG
17 NVRVD, 3 JNWZP => 8 VPVL
53 STKFG, 6 MNCFX, 46 VJHF, 81 HVMC, 68 CXFTF, 25 GNMV => 1 FUEL
22 VJHF, 37 MNCFX => 5 FWMGM
139 ORE => 4 NVRVD
144 ORE => 7 JNWZP
5 MNCFX, 7 RFSQX, 2 FWMGM, 2 VPVL, 19 CXFTF => 3 HVMC
5 VJHF, 7 MNCFX, 9 VPVL, 37 CXFTF => 6 GNMV
145 ORE => 6 MNCFX
1 NVRVD => 8 CXFTF
1 VJHF, 6 MNCFX => 4 RFSQX
176 ORE => 6 VJHF');
/** @var Reaction[] $reactions */
$reactions = [];

/** @var Substance $fuel */
$fuel = null;

foreach ($input as $line) {
    [ $inputs, $output ] = explode('=>', $line, 2);
    [ $outputAmount, $outputName ] = explode(' ', trim($output), 2);
    $inputs = array_map(function (string $input): Substance {
        [ $amount, $name ] = explode(' ', trim($input), 2);

        return new Substance($name, intval($amount));
    }, explode(',', trim($inputs)));

    $output = new Substance($outputName, intval($outputAmount));
    $reactions[] = new Reaction($inputs, $output);

    if ($output->name === 'FUEL') {
        $fuel = clone $output;
        $fuel->amount = 1;
    }
}

/** @var Substance[] $toCreate */
$toCreate = [ $fuel ];
$shoppingList = [];

while (!empty($toCreate)) {
    $substance = array_shift($toCreate);

    if ($substance->name === 'ORE') { // ore
        continue;
    }

    $reaction = findReactionForSubstance($substance->name, $reactions);
    $multiplier = ceil($substance->amount / $reaction->output->amount);

    foreach ($reaction->inputs as $input) {
        if (!array_key_exists($input->name, $shoppingList)) {
            $shoppingList[$input->name] = 0;
        }

        $shoppingList[$input->name] += ($multiplier * $input->amount);
    }

    $toCreate = array_merge($toCreate, $reaction->inputs);
}

//$oreRequired = 0;

foreach ($shoppingList as $name => $amount) {
    echo sprintf('%s => %d'.PHP_EOL, $name, $amount);
    $reaction = findReactionForSubstance($name, $reactions);

    if ($reaction === null || count($reaction->inputs) > 1 || $reaction->inputs[0]->name !== 'ORE') {
        continue;
    }

    $timesToRunReaction = ceil($amount / $reaction->output->amount);
    $oreRequired = $timesToRunReaction * $reaction->inputs[0]->amount;

}

//var_dump($oreRequired);
