<?php

class Fabric
{
    public $id;
    public $x;
    public $y;
    public $w;
    public $h;
    public $overlaps = false;

    public function __construct(int $id, int $x, int $y, int $w, int $h)
    {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }

    public static function intersects(self $a, self $b) : bool
    {
        return !($a->x > $b->x + $b->w ||
            $a->x + $a->w < $b->x ||
            $a->y > $b->y + $b->h ||
            $a->y + $a->h < $b->y);
    }
}

$fh = fopen(__DIR__.'/inputs/03.txt', 'r');
$fabrics = [];

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

    $fabrics[] = new Fabric(intval($matches[1]), intval($matches[2]), intval($matches[3]), intval($matches[4]), intval($matches[5]));
}

fclose($fh);

$fabricCount = count($fabrics);

for ($i = 0; $i < $fabricCount; $i++) {
    $a = $fabrics[$i];

    for ($j = 0; $j < $fabricCount; $j++) {
        $b = $fabrics[$j];
        if ($i === $j) {
            continue;
        }

        if (Fabric::intersects($a, $b)) {
            $a->overlaps = true;
            $b->overlaps = true;
        }
    }
}

$noOverlap = array_filter($fabrics, function(Fabric $f) {
    return !$f->overlaps;
});

var_dump($noOverlap);
