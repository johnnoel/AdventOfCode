<?php

$rawPoints = explode("\n", trim(file_get_contents(__DIR__.'/inputs/10.txt')));
$points = [];

class Point
{
    public $x;
    public $y;
    public $vx;
    public $vy;

    public function __construct(int $x, int $y, int $vx, int $vy)
    {
        $this->x = $x;
        $this->y = $y;
        $this->vx = $vx;
        $this->vy = $vy;
    }
}

function printPoints(array &$points, array $bounding) : string
{
    [ $minX, $minY, $maxX, $maxY ] = $bounding;
    $width = abs($maxX - $minX);
    $height = abs($maxY - $minY);
    $ret = '';

    for ($y = $minY; $y <= $maxY; $y++) {
        for ($x = $minX; $x <= $maxX; $x++) {
            $p = null;

            foreach ($points as $point) {
                if ($point->x === $x && $point->y === $y) {
                    $p = $point;
                    break;
                }
            }

            $ret .= ($p !== null) ? '#' : '.';
        }

        $ret .= PHP_EOL;
    }

    return $ret;
}

foreach ($rawPoints as $rawPoint) {
    //position=< 52503,  21082> velocity=<-5, -2>
    $matches = [];
    $matched = preg_match('/^position=<([-\s\d]+),([-\s\d]+)> velocity=<([-\s\d]+),([-\s\d]+)>$/', $rawPoint, $matches);

    if ($matched !== 1) {
        exit;
    }

    $point = new Point(intval($matches[1]), intval($matches[2]), intval($matches[3]), intval($matches[4]));
    $points[] = $point;
}

$minArea = PHP_INT_MAX;
$convergencePoint = null;

for ($i = 1; $i <= 25000; $i++) {
    $maxX = 0; $maxY = 0;
    $minX = PHP_INT_MAX; $minY = PHP_INT_MAX;

    foreach ($points as $point) {
        $point->x += $point->vx;
        $point->y += $point->vy;

        $maxX = max($point->x, $maxX);
        $maxY = max($point->y, $maxY);
        $minX = min($point->x, $minX);
        $minY = min($point->y, $minY);
    }

    $area = (($minX * $maxY) - ($minY * $maxX)) / 2;

    if ($area > $minArea) {
        foreach ($points as $point) {
            $point->x -= $point->vx;
            $point->y -= $point->vy;

            $maxX = max($point->x, $maxX);
            $maxY = max($point->y, $maxY);
            $minX = min($point->x, $minX);
            $minY = min($point->y, $minY);
        }

        echo printPoints($points, [ $minX, $minY, $maxX, $maxY ]);
        echo ($i - 1).' seconds'.PHP_EOL;
        break;
    }

    $minArea = $area;
}
