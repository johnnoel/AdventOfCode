<?php

$lines = explode("\n", rtrim(file_get_contents(__DIR__.'/inputs/13.txt')));

$maxX = strlen($lines[0]);
$maxY = count($lines);

class Track
{
    /** @var string */
    public $piece;
    /** @var Track */
    public $top;
    /** @var Track */
    public $right;
    /** @var Track */
    public $bottom;
    /** @var Track */
    public $left;
    /** @var int */
    public $x;
    /** @var int */
    public $y;

    public function __construct(string $piece, int $x, int $y)
    {
        $this->piece = $piece;
        $this->x = $x;
        $this->y = $y;
    }
}

class Train
{
    /** @var Track */
    public $track;
    /** @var string */
    public $direction;
    /** @var string */
    public $lastTurn = null;

    public function __construct(Track $track, string $direction)
    {
        $this->track = $track;
        $this->direction = $direction;
    }
}

class Direction
{
    const LEFT = 'LEFT';
    const RIGHT = 'RIGHT';
    const UP = 'UP';
    const DOWN = 'DOWN';
}

$grid = array_fill(0, $maxX, array_fill(0, $maxY, null));

$x = -1;
$y = -1;
foreach ($lines as $line) {
    $y++;
    $pieces = str_split($line);
    foreach ($pieces as $piece) {
        $x++;
        if (empty($piece)) {
            continue;
        }

        $grid[$x][$y] = new Track($piece, $x, $y);
    }

    $x = -1;
}

$trains = [];

for ($y = 0; $y < $maxY; $y++) {
    for ($x = 0; $x < $maxX; $x++) {
        $track = $grid[$x][$y];
        if (!($track instanceof Track)) {
            continue;
        }

        switch ($track->piece) {
        case '|':
            $track->top = $grid[$x][$y-1];
            $track->bottom = $grid[$x][$y+1];
            break;
        case '-':
            $track->left = $grid[$x-1][$y];
            $track->right = $grid[$x+1][$y];
            break;
        case '/':
            // is this bottom right?
            if (array_key_exists($x-1, $grid) && $grid[$x-1][$y] !== null && in_array($grid[$x-1][$y]->piece, [ '>', '<', '-', '+', '\\' ])) {
                $track->top = $grid[$x][$y-1];
                $track->left = $grid[$x-1][$y];
            } else { // or top left?
                $track->bottom = $grid[$x][$y+1];
                $track->right = $grid[$x+1][$y];
            }
            break;
        case '\\':
            // is this top right?
            if (array_key_exists($x-1, $grid) && $grid[$x-1][$y] !== null && in_array($grid[$x-1][$y]->piece, [ '>', '<', '-', '+', '/' ])) {
                $track->bottom = $grid[$x][$y+1];
                $track->left = $grid[$x-1][$y];
            } else { // or bottom left?
                $track->top = $grid[$x][$y-1];
                $track->right = $grid[$x+1][$y];
            }
            break;
        case '+':
            $track->top = $grid[$x][$y-1];
            $track->right = $grid[$x+1][$y];
            $track->bottom = $grid[$x][$y+1];
            $track->left = $grid[$x-1][$y];
            break;
        // trains, assuming they never start on a corner or an intersection
        case '>':
            $trains[] = new Train($track, Direction::RIGHT);
            $track->piece = '-';
            $track->left = $grid[$x-1][$y];
            $track->right = $grid[$x+1][$y];
            break;
        case '<':
            $trains[] = new Train($track, Direction::LEFT);
            $track->piece = '-';
            $track->left = $grid[$x-1][$y];
            $track->right = $grid[$x+1][$y];
            break;
        case '^':
            $trains[] = new Train($track, Direction::UP);
            $track->piece = '|';
            $track->top = $grid[$x][$y-1];
            $track->bottom = $grid[$x][$y+1];
            break;
        case 'v':
            $trains[] = new Train($track, Direction::DOWN);
            $track->piece = '|';
            $track->top = $grid[$x][$y-1];
            $track->bottom = $grid[$x][$y+1];
            break;
        }
    }
}

$crashed = false;
while (!$crashed) {
    foreach ($trains as $train) {
        switch ($train->direction) {
        case Direction::UP:
            $nextTrack = $train->track->top;
            if ($nextTrack->piece === '/') {
                $train->direction = Direction::RIGHT;
            } else if ($nextTrack->piece === '\\') {
                $train->direction = Direction::LEFT;
            } else if ($nextTrack->piece === '+') {
                if ($train->lastTurn === null || $train->lastTurn === Direction::RIGHT) {
                    $train->direction = Direction::LEFT;
                } else if ($train->lastTurn === Direction::LEFT) {
                } else if ($train->lastTurn === Direction::UP || $train->lastTurn === Direction::DOWN) {
                    $train->direction = null;
                }
            }
            break;
        case Direction::DOWN:
            break;
        case Direction::LEFT:
            break;
        case Direction::RIGHT:
            break;
        }
    }
}

//var_dump(count($trains));
