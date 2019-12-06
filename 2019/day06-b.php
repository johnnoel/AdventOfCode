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

$you = null;
/** @var Orbiter $orbiter */
foreach ($orbiters as $orbiter) {
    if ($orbiter->name === 'YOU') {
        $you = $orbiter;
        break;
    }
}

$path = [ $you ];
function recurse(Orbiter $o, array $pathSoFar, array &$solutions, array &$visited): void
{
    if ($o->name === 'SAN') {
        $solutions[] = $pathSoFar;
        return;
    }

    $visited[$o->name] = true;

    $toCheck = array_merge([ $o->orbitsAround ], $o->orbiters ?? []);
    foreach ($toCheck as $tc) {
        if ($tc === null || array_key_exists($tc->name, $visited)) {
            continue;
        }

        $newPath = array_merge([], $pathSoFar, [ $tc ]);
        recurse($tc, $newPath, $solutions, $visited);
    }
}

$solutions = [];
$visited = [ 'YOU' => true ];
recurse($you, $path, $solutions, $visited);

foreach ($solutions as $solution) {
    foreach ($solution as $orbiter) {
        echo $orbiter->name.PHP_EOL;
    }

    echo count($solution).PHP_EOL;
}
