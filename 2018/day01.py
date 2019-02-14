import functools

def parse(num):
    if num[0:1] == '-':
        return 0 - int(num[1:])

    return int(num[1:])

with open('inputs/01.txt', 'r') as f:
    data = [parse(x) for x in f.read().splitlines()]
    part_1 = functools.reduce(lambda x, y: x + y, data, 0)

    freqs = set()
    part_2 = None
    current = 0

    while part_2 is None:
        for freq in data:
            current += freq

            if current in freqs:
                part_2 = current
                break

            freqs.add(current)

    print(part_1, part_2)
