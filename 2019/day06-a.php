<?php

class Orbiter
{
    /** @var Orbiter */
    public $orbitsAround;
    /** @var Orbiter[] */
    public $orbiters;
    /** @var string */
    public $name;
    /** @var int */
    public $depth = 0;

    /**
     * @param string $name
     * @param Orbiter|null $orbitsAround
     */
    public function __construct(string $name, ?Orbiter $orbitsAround)
    {
        $this->name = $name;
        $this->orbitsAround = $orbitsAround;
    }
}

$input = array_filter(explode("\n", file_get_contents(__DIR__.'/day06.txt')));
$orbiters = [];

foreach ($input as $orbitPair) {
    [ $a, $b ] = explode(')', $orbitPair); // b is in orbit around a
    $ao = null;
    $bo = null;

    if (!array_key_exists($a, $orbiters)) {
        $ao = new Orbiter($a, null);
        $orbiters[$a] = $ao;
    } else {
        $ao = $orbiters[$a];
    }

    if (!array_key_exists($b, $orbiters)) {
        $bo = new Orbiter($b, $ao);
        $orbiters[$b] = $bo;
    } else {
        $bo = $orbiters[$b];
    }

    $bo->orbitsAround = $ao;
    $ao->orbiters[] = $bo;
}

$root = null;
/** @var Orbiter $orbiter */
foreach ($orbiters as $orbiter) {
    if ($orbiter->orbitsAround === null) {
        $root = $orbiter;
        break;
    }
}

/** @var Orbiter[] $queue */
$queue = [ $root ];
while (!empty($queue)) {
    $current = array_shift($queue);
    $children = $current->orbiters;

    if (empty($children)) {
        continue;
    }

    array_walk($children, function (Orbiter $o) use ($current): void {
        $o->depth = $current->depth + 1;
    });

    $queue = array_merge($queue, $children);
}

$depths = array_map(function (Orbiter $o): int {
    return $o->depth;
}, $orbiters);

var_dump(array_sum($depths));
