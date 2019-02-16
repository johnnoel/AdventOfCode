import re
import collections

Fabric = collections.namedtuple('Fabric', [ 'id', 'x', 'y', 'w', 'h' ])

def parse(line):
    m = re.search('^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$', line)
    return Fabric(id=int(m.group(1)), x=int(m.group(2)), y=int(m.group(3)), w=int(m.group(4)), h=int(m.group(5)))

def intersects(f1, f2):
    return not (f1.x > f2.x + f2.w or
            f1.x + f1.w < f2.x or
            f1.y > f2.y + f2.h or
            f1.y + f1.h < f2.y)

with open('inputs/03.txt', 'r') as f:
    lines = f.read().splitlines()
    fabrics = {parse(line) for line in lines}

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

    collisions = set()
    for f1 in fabrics:
        for f2 in fabrics:
            if f1 == f2 or f2 in collisions:
                continue

            if intersects(f1, f2):
                collisions.add(f1)
                collisions.add(f2)

    part_2 = fabrics - collisions

    print(part_1, part_2)
