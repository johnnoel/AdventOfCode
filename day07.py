f = open('inputs/07.txt', 'r')
rules = set([ (line[5:6], line[-12:-11]) for line in f.read().splitlines() ])

all_steps = set([ chr(v) for v in range(65, 91) ])
befores = { v: set() for v in all_steps }

for rule in rules:
    befores[rule[1]].add(rule[0])

first_steps = set([ k for k in befores if len(befores[k]) == 0 ])
performed = []
next_steps = first_steps.copy()

while len(performed) < 26:
    performed.append(sorted(next_steps)[0])
    p = set(performed)
    next_steps = set([ k for k in befores if len(befores[k] - p) == 0 ]) - p

part_1 = ''.join(performed)

workers = 6
work = set()
second = 0
performed = []
next_steps = first_steps.copy()

while len(performed) < 26:
    new_work = set([ w for w in work if second < (w[1] + (ord(w[0]) - 4)) ])
    performed.extend([ w[0] for w in work - new_work ])
    work = new_work

    if len(work) < workers:
        work |= set([ (step, second) for step in sorted(next_steps)[:(workers - len(work))] ])

    p = set(performed)
    #next_steps = set([ k for k in befores if len(befores[k] - p) == 0]) - p
    for k in befores:

    second += 1

part_2 = second

print(part_1, second)
