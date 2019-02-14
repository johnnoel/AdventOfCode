import functools

def parse(num):
    if num[0:1] == '-':
        return 0 - int(num[1:])

    return int(num[1:])

with open('inputs/01.txt', 'r') as f:
    data = [parse(x) for x in f.read().splitlines()]
    value = functools.reduce(lambda x, y: x + y, data, 0)
    print(value)
