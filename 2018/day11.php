<?php

$input = 8141;
$grid = array_fill(1, 300, array_fill(1, 300, 0));

for ($y = 1; $y <= 300; $y++) {
    for ($x = 1; $x <= 300; $x++) {
        $rackId = $x + 10;
        $powerLevel = (($rackId * $y) + $input) * $rackId;
        $hundredsDigit = ($powerLevel > 99) ? intval(substr($powerLevel.'', -3, 1)) : 0;

        $grid[$x][$y] = $hundredsDigit - 5;
    }
}

$maxPower = 0;
$maxX = 0; $maxY = 0;
$maxSquare = 0;

$cache = array_fill(1, 297, array_fill(1, 297, null));

for ($squareSize = 3; $squareSize <= 300; $squareSize++) {
    echo '.';
    $powers = array_fill(1, (300 - $squareSize), array_fill(1, (300 - $squareSize), 0));

    for ($y = 1; $y <= (300 - $squareSize); $y++) {
        for ($x = 1; $x <= (300 - $squareSize); $x++) {
            $power = 0;

            if ($cache[$x][$y] !== null) {
                $power = $cache[$x][$y];

                for ($i = 0; $i < $squareSize; $i++) {
                    $power += $grid[$x+($squareSize-1)][$y+$i];
                }

                for ($i = 0; $i < ($squareSize - 1); $i++) {
                    $power += $grid[$x+$i][$y+($squareSize-1)];
                }
            } else {
                for ($i = 0; $i < $squareSize; $i++) {
                    for ($j = 0; $j < $squareSize; $j++) {
                        $power += $grid[$x+$j][$y+$i];
                    }
                }
            }

            $powers[$x][$y] = $power;
            //echo $x.','.$y.' = '.$power.PHP_EOL;

            if ($power > $maxPower) {
                $maxPower = $power;
                $maxX = $x;
                $maxY = $y;
                $maxSquare = $squareSize;
            }
        }
    }

    $cache = $powers;
}

echo PHP_EOL.$maxX.','.$maxY.','.$maxSquare.' = '.$maxPower.PHP_EOL;
