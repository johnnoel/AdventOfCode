<?php

class Fabric
{
    public $id;
    public $x;
    public $y;
    public $w;
    public $h;

    public function __construct(int $id, int $x, int $y, int $w, int $h)
    {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }
}

$fh = fopen(__DIR__.'/inputs/03.txt', 'r');
//$fabrics = [];
// $material[x][y] = [ ids ]
$material = [];

$maxX = 0;
$maxY = 0;

while (!feof($fh)) {
    $line = trim(fgets($fh));
    if (empty($line)) {
        continue;
    }

    $matches = [];
    $matched = preg_match('/^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$/', $line, $matches);

    if ($matched !== 1) {
        continue;
    }

    $f = new Fabric(intval($matches[1]), intval($matches[2]), intval($matches[3]), intval($matches[4]), intval($matches[5]));
    $maxX = max($maxX, $f->x + $f->w);
    $maxY = max($maxY, $f->y + $f->h);

    for ($y = $f->y; $y < ($f->y + $f->h); $y++) {
        for ($x = $f->x; $x < ($f->x + $f->w); $x++) {
            if (!array_key_exists($x, $material)) {
                $material[$x] = [];
            }

            if (!array_key_exists($y, $material[$x])) {
                $material[$x][$y] = 0;
            }

            $material[$x][$y]++;
        }
    }
}

fclose($fh);

$coverage = 0;

for ($i = 0; $i <= $maxY; $i++) {
    for ($j = 0; $j <= $maxX; $j++) {
        if (array_key_exists($j, $material) && array_key_exists($i, $material[$j])) {
            if ($material[$j][$i] > 1) {
                $coverage++;
            }
        }
    }
}

/*$coverage = 0;
array_walk_recursive($material, function($item, $key) use (&$coverage) {
    if ($item > 1) {
        $coverage++;
    }
});*/

var_dump($coverage);
