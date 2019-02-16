import re
import collections

Fabric = collections.namedtuple('Fabric', [ 'id', 'x', 'y', 'w', 'h' ])

def parse(line):
    m = re.search('^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$', line)
    return Fabric(id=int(m.group(1)), x=int(m.group(2)), y=int(m.group(3)), w=int(m.group(4)), h=int(m.group(5)))

with open('inputs/03.txt', 'r') as f:
    lines = f.read().splitlines()
    fabrics = [parse(line) for line in lines]

    min_x = min([f.x for f in fabrics])
    min_y = min([f.y for f in fabrics])
    max_x = max([f.x + f.w for f in fabrics ])
    max_y = max([f.y + f.h for f in fabrics ])

    grid = [ [ 0 for i in range(0, max_x + 1) ] for i in range(0, max_y + 1) ]

    for f in fabrics:
        for y in range(f.y, f.y + f.h):
            for x in range(f.x, f.x + f.w):
                grid[x][y] += 1

    part_1 = sum([ sum([ 1 if count > 1 else 0 for count in inner ]) for inner in grid ])

    print(part_1, a)
