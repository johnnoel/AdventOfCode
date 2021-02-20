import re
from datetime import datetime

class Log:
    def __init__(self, datetime, guard_id, start, awake, asleep):
        self.datetime = datetime
        self.guard_id = guard_id
        self.start = start
        self.awake = awake
        self.asleep = asleep

class Guard:
    def __init__(self, id, total, minutes):
        self.id = id
        self.minutes = minutes

def parse(line):
    m = re.search('^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2})\] (wakes up|falls asleep|Guard #(\d+) begins shift)$', line)
    dt = datetime.fromisoformat(m.group(1))
    guard_id = None
    start = False
    awake = (m.group(2) == 'wakes up')
    asleep = (m.group(2) == 'falls asleep')

    if not awake and not asleep:
        start = True
        #awake = True
        guard_id = int(m.group(3))

    return Log(dt, guard_id, start, awake, asleep)

with open('inputs/04.txt', 'r') as f:
    lines = f.read().splitlines()
    logs = [parse(line) for line in lines]
    logs.sort(key=lambda v: v.datetime)

    current_guard = None
    asleep = None
    guards = {}

    for log in logs:
        if log.start:
            if log.guard_id not in guards:
                current_guard = Guard(log.guard_id, 0, [0 for i in range(0, 60)])
                guards[log.guard_id] = current_guard
            else:
                current_guard = guards[log.guard_id]

        if log.asleep:
            asleep = log.datetime

        if log.awake:
            for minute in range(asleep.minute, log.datetime.minute):
                current_guard.minutes[minute] += 1

            asleep = None

    sorted_guards = [ guard for guard in guards.values() ]
    sorted_guards.sort(key=lambda v: sum(v.minutes), reverse=True)

    sleepy_guard = sorted_guards[0]

    part_1 = (sleepy_guard.id, sleepy_guard.minutes.index(max(sleepy_guard.minutes)))

    max_sleep = 0
    part_2 = ()

    for guard in guards.values():
        most_sleep = max(guard.minutes)
        if most_sleep > max_sleep:
            part_2 = (guard.id, guard.minutes.index(most_sleep))
            max_sleep = most_sleep

    print(part_1[0] * part_1[1], part_2[0] * part_2[1])
