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

    public function __toString(): string
    {
        return $this->name;
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

$you = null;
/** @var Orbiter $orbiter */
foreach ($orbiters as $orbiter) {
    if ($orbiter->name === 'YOU') {
        $you = $orbiter;
        break;
    }
}

function recurse(Orbiter $o, array $pathSoFar, array &$solutions): void
{
    $pathSoFar[] = $o;

    if ($o->name === 'SAN') {
        $solutions[] = $pathSoFar;
        return;
    }

    $possible = array_diff(
        array_merge($o->orbiters ?? [], [ $o->orbitsAround ]),
        $pathSoFar
    );

    foreach ($possible as $tc) {
        if ($tc === null) {
            continue;
        }

        recurse($tc, $pathSoFar, $solutions);
    }
}

$path = [];
$solutions = [];
recurse($you, $path, $solutions);

foreach ($solutions as $solution) {
    foreach ($solution as $orbiter) {
        echo $orbiter->name.' -> ';
    }

    echo PHP_EOL.(count($solution) - 3).PHP_EOL.PHP_EOL;
}
