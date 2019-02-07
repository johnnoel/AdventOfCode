<?php

gc_disable();
ini_set('memory_limit', '1024M');

$input = trim(file_get_contents(__DIR__.'/inputs/09.txt'));

$matches = [];
$matched = preg_match('/^(\d+) players; last marble is worth (\d+) points$/', $input, $matches);

if ($matched !== 1) {
    exit;
}

$playerCount = intval($matches[1]);
//$playerCount = 9;
$lastMarbleValue = intval($matches[2]) * 100;
//$lastMarbleValue = 25;

$playerScores = array_fill(1, $playerCount, 0);

class Marble
{
    /** @var int */
    public $value;
    /** @var Marble */
    public $next;
    /** @var Marble */
    public $prev;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}

$zeroMarble = new Marble(0);

$current = $zeroMarble;
$current->next = $current;
$current->prev = $current;

$value = 1;

function printCircle(Marble $zero, Marble $active)
{
    $current = $zero;
    do {
        if ($active === $current) {
            echo '_'.$current->value.'_ ';
        } else {
            echo $current->value.' ';
        }

        $current = $current->next;
    } while($current !== $zero);
    echo PHP_EOL;
}

while (true) {
    for ($player = 1; $player <= $playerCount; $player++) {
        if ($value % 23 === 0) {
            $toRemove = $current;
            for ($j = 0; $j < 7; $j++) {
                $toRemove = $toRemove->prev;
            }

            $score = $value + $toRemove->value;
            $playerScores[$player] += $score;

            $toRemove->prev->next = $toRemove->next;
            $toRemove->next->prev = $toRemove->prev;
            $current = $toRemove->next;
            unset($toRemove);

            //printCircle($zeroMarble, $current);
        } else {
            $marble = new Marble($value);

            $next = $current->next->next;
            $prev = $current->next;

            $marble->next = $next;
            $marble->prev = $prev;

            $next->prev = $marble;
            $prev->next = $marble;

            $current = $marble;

            //printCircle($zeroMarble, $current);
        }

        if ($value === $lastMarbleValue) {
            break 2;
        }

        $value++;
    }
}

arsort($playerScores);
reset($playerScores);
var_dump(key($playerScores), current($playerScores));
