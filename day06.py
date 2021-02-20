from collections import defaultdict

f = open('inputs/06.txt', 'r')
coords = set([ (int(line.split(',')[0]), int(line.split(',')[1])) for line in f.read().splitlines() ])

def manhattan(c1, c2):
    return abs(c1[0] - c2[0]) + abs(c1[1] - c2[1]);

max_x = max(coords, key=lambda v: v[0])[0]
max_y = max(coords, key=lambda v: v[1])[1]

grid = {}
part_2 = 0

for y in range(0, max_y):
    for x in range(0, max_x):
        distances = { coord: manhattan(coord, (x, y)) for coord in coords }
        min_distance = min([ v for v in distances.values() ])
        min_distance_coords = [ coord for coord in distances if distances[coord] == min_distance ]

        if len(min_distance_coords) == 1:
            grid[(x, y)] = min_distance_coords[0]

        if sum(distances.values()) < 10000:
            part_2 += 1

infinites = set()
areas = defaultdict(int)

for xy in grid:
    if xy[0] == 0 or xy[0] == max_x or xy[1] == 0 or xy[1] == max_y:
        infinites.add(grid[xy])

    if grid[xy] not in infinites:
        areas[grid[xy]] += 1

part_1 = max(set(areas.values()) - infinites)

print(part_1, part_2)
