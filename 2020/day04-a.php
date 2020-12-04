<?php

$passports = preg_split('/\\n\\n/', trim(file_get_contents(__DIR__ . '/day04.txt')));
$required = [ 'byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid' ];
$valid = 0;

foreach ($passports as $passport) {
    $clean = preg_replace('/\\n/', ' ', $passport);
    $kvs = array_map(fn ($kv) => explode(':', $kv), explode(' ', $clean));

    $ks = array_map(fn ($a) => $a[0], $kvs);

    $valid += (count(array_intersect($required, $ks)) === count($required)) ? 1 : 0;
}

echo $valid . PHP_EOL;