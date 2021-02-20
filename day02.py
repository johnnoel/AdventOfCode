with open('inputs/02.txt', 'r') as f:
    data = f.read().splitlines()
    twos = 0
    threes = 0

    for box_id in data:
        letters = {}
        for letter in box_id:
            if letter in letters:
                letters[letter] += 1
            else:
                letters[letter] = 1

        twos += 1 if len({ k: v for k, v in letters.items() if v == 2 }) > 0 else 0
        threes += 1 if len({ k: v for k, v in letters.items() if v == 3 }) > 0 else 0

    part_1 = twos * threes;
    part_2_ids = ()

    for box_id_a in data:
        for box_id_b in data:
            diffs = 0

            for idx in range(0, len(box_id_a)):
                if box_id_a[idx:idx+1] != box_id_b[idx:idx+1]:
                    diffs += 1
                    if diffs >= 2:
                        break

            if diffs == 1:
                part_2_ids = (box_id_a, box_id_b)

        if len(part_2_ids) == 2:
            break
    
    part_2 = []
    for idx in range(0, len(part_2_ids[0])):
        if part_2_ids[0][idx:idx+1] == part_2_ids[1][idx:idx+1]:
            part_2.append(part_2_ids[0][idx:idx+1])

    print(part_1, ''.join(part_2))
