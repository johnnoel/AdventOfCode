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

// map the guards and times slept on each minute
$guardMinuteMap = [];
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
        $minStart = intval($fellAsleep->format('i'));
        $minEnd = intval($l->when->format('i'));

        if (!array_key_exists($l->guardId, $guardMinuteMap)) {
            $guardMinuteMap[$l->guardId] = array_fill(0, 59, 0);
        }

        for ($i = $minStart; $i < $minEnd; $i++) {
            $guardMinuteMap[$l->guardId][$i]++;
        }
    }
}

$sleepiestGuardId = null;
$sleepiestMinute = null;
$sleepiestMinuteCount = null;

foreach ($guardMinuteMap as $guardId => $minutes) {
    asort($minutes, SORT_NUMERIC);
    $mins = array_reverse($minutes, true);

    $sm = key($mins);
    $smc = $mins[$sm];

    if ($smc > $sleepiestMinuteCount) {
        $sleepiestMinute = $sm;
        $sleepiestMinuteCount = $smc;
        $sleepiestGuardId = $guardId;
    }
}

var_dump($sleepiestGuardId * $sleepiestMinute);
