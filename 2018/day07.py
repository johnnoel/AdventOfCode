f = open('inputs/07.txt', 'r')
rules = set([ (line[5:6], line[-12:-11]) for line in f.read().splitlines() ])

all_steps = set([ chr(v) for v in range(65, 91) ])
befores = { v: set() for v in all_steps }

for rule in rules:
    befores[rule[1]].add(rule[0])

performed = []
next_steps = set([ k for k in befores if len(befores[k]) == 0 ])

while len(performed) < 26:
    performed.append(sorted(next_steps)[0])
    p = set(performed)
    next_steps = set([ k for k in befores if len(befores[k] - p) == 0 ]) - p

part_1 = ''.join(performed)

print(part_1)
