<?php

class Log
{
    public $when;
    public $guardId;
    public $beginShift = false;
    public $fallsAsleep = false;
    public $wakesUp = false;

    public function __construct(DateTimeImmutable $when, bool $beginShift, bool $fallsAsleep, bool $wakesUp, ?int $guardId = null)
    {
        $this->when = $when;
        $this->beginShift = $beginShift;
        $this->fallsAsleep = $fallsAsleep;
        $this->wakesUp = $wakesUp;
        $this->guardId = $guardId;
    }
}

$fh = fopen(__DIR__.'/inputs/04.txt', 'r');
$logs = [];

// parse log entries
while (!feof($fh)) {
    $line = trim(fgets($fh));
    if (empty($line)) {
        continue;
    }

    $matches = [];
    $matched = preg_match('/^\\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2})\\] (.*)$/', $line, $matches);

    if ($matched !== 1) {
        continue;
    }

    $when = DateTimeImmutable::createFromFormat('Y-m-d H:i', $matches[1]);
    $beginShift = false;
    $fallsAsleep = false;
    $wakesUp = false;
    $guardId = null;

    if ($matches[2] === 'wakes up') {
        $wakesUp = true;
    } else if ($matches[2] === 'falls asleep') {
        $fallsAsleep = true;
    } else {
        $beginShift = true;
        $guardId = intval(substr(explode(' ', $matches[2])[1], 1));
    }

    $logs[] = new Log($when, $beginShift, $fallsAsleep, $wakesUp, $guardId);
}

// sort by when the log entry was made
usort($logs, function(Log $a, Log $b) {
    return $a->when <=> $b->when;
});

// find the sleepiest guards by minutes slept
$sleepiestGuards = [];
$currentGuardId = $logs[0]->guardId;
$fellAsleep = null;

foreach ($logs as $l) {
    if ($l->guardId === null) {
        $l->guardId = $currentGuardId;
    }

    if ($l->beginShift) {
        $currentGuardId = $l->guardId;
    }

    if ($l->fallsAsleep) {
        $fellAsleep = $l->when;
    }

    if ($l->wakesUp) {
        $timeAsleep = intval(($l->when->diff($fellAsleep))->format('%i')) - 1;

        if (!array_key_exists($l->guardId, $sleepiestGuards)) {
            $sleepiestGuards[$l->guardId] = 0;
        }

        $sleepiestGuards[$l->guardId] += $timeAsleep;
    }
}

asort($sleepiestGuards, SORT_NUMERIC);
$sleepiestGuards = array_reverse($sleepiestGuards, true);
$sleepiestGuardId = key($sleepiestGuards); // array cursor is rewound

// get all of the log entries pertaining to the sleepiest guard
$sleepyLogs = array_filter($logs, function(Log $l) use ($sleepiestGuardId) {
    return $l->guardId === $sleepiestGuardId;
});

// find the sleepiest minute
$sleepyMinutes = array_fill(0, 59, 0);
$fellAsleep = null;

foreach ($sleepyLogs as $l) {
    if ($l->fallsAsleep) {
        $fellAsleep = $l->when;
    }

    if ($l->wakesUp) {
        $minStart = intval($fellAsleep->format('i'));
        $minEnd = intval($l->when->format('i'));

        for ($i = $minStart; $i < $minEnd; $i++) {
            $sleepyMinutes[$i]++;
        }
    }
}

asort($sleepyMinutes, SORT_NUMERIC);
$sleepyMinutes = array_reverse($sleepyMinutes, true);

var_dump(key($sleepyMinutes) * $sleepiestGuardId);
