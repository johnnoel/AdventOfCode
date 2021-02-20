f = open('inputs/05.txt', 'r')
polymer = f.read().strip()

def collapse(polymer, remove=None):
    new_polymer = polymer[:1]

    for char in polymer[1:]:
        if remove != None and char in remove:
            continue
        if char != new_polymer[-1:] and char.upper() == new_polymer[-1:].upper():
            new_polymer = new_polymer[:-1]
        else:
            new_polymer += char

    if len(new_polymer) == len(polymer):
        return new_polymer

    return collapse(new_polymer, remove)

part_1 = len(collapse(polymer))
part_2 = min([ len(collapse(polymer, chr(cp)+chr(cp).upper())) for cp in range(97, 123) ])

print(part_1, part_2)
