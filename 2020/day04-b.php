<?php

$passports = preg_split('/\\n\\n/', trim(file_get_contents(__DIR__ . '/day04.txt')));
$required = [ 'byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid' ];
$valid = 0;

foreach ($passports as $passport) {
    $clean = preg_replace('/\\n/', ' ', $passport);
    $kvs = array_map(fn ($kv) => explode(':', $kv), explode(' ', $clean));

    $ks = array_map(fn ($a) => $a[0], $kvs);
    $vs = array_map(fn ($a) => $a[1], $kvs);

    if (count(array_intersect($required, $ks)) !== count($required)) {
        continue;
    }

    $p = array_combine($ks, $vs);

    if ($p['byr'] < 1920 || $p['byr'] > 2002) {
        continue;
    }

    if ($p['iyr'] < 2010 || $p['iyr'] > 2020) {
        continue;
    }

    if ($p['eyr'] < 2020 || $p['eyr'] > 2030) {
        continue;
    }

    $heightMatches = [];
    $matched = preg_match('/^(\d{2,3})(cm|in)$/i', $p['hgt'], $heightMatches);

    if ($matched !== 1) {
        continue;
    }

    if ($heightMatches[2] === 'cm' && ($heightMatches[1] < 150 || $heightMatches[1] > 193)) {
        continue;
    }

    if ($heightMatches[2] === 'in' && ($heightMatches[1] < 59 || $heightMatches[1] > 76)) {
        continue;
    }

    if (preg_match('/^#[0-9a-f]{6}$/i', $p['hcl']) !== 1) {
        continue;
    }

    if (!in_array($p['ecl'], [ 'amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth' ])) {
        continue;
    }

    if (preg_match('/^\d{9}$/', $p['pid']) !== 1) {
        continue;
    }

    $valid++;
}

echo $valid . PHP_EOL;
