<?php

class Dir
{
    /** @var array<Dir> */
    public array $subdirs = [];
    /** @var array<File> */
    public array $files = [];

    public function __construct(public readonly string $name, public readonly ?Dir $parent = null)
    {
    }

    public function totalFileSize(): int
    {
        return array_sum(array_map(fn(File $f): int => $f->size, $this->files));
    }

    public function size(): int
    {
        return array_sum(array_map(fn(Dir $d): int => $d->size(), $this->subdirs)) + $this->totalFileSize();
    }
}

class File
{
    public function __construct(public readonly string $name, public readonly int $size)
    {
    }
}

class Shell
{
    public ?Dir $currentDir = null;
}

function fileTree(Dir $d, int $indent = 0)
{
    foreach ($d->subdirs as $subdir) {
        echo str_repeat(' ', $indent) . $subdir->name . PHP_EOL;
        fileTree($subdir, $indent + 1);
    }

    foreach ($d->files as $file) {
        echo str_repeat(' ', $indent) . $file->name . ' (' . $file->size . ')' . PHP_EOL;
    }
}

function doot(Dir $d, int $currentFreeSpace, int $freeSpaceNeeded): array
{
    $ret = [];
    $dirSize = $d->size();

    //echo sprintf('%s (%d + %d = %d)' . PHP_EOL, $d->name, $currentFreeSpace, $dirSize, ($currentFreeSpace + $dirSize));

    if (($currentFreeSpace + $dirSize) >= $freeSpaceNeeded) {
        $ret[] = [ $d, $dirSize ];
    }

    foreach ($d->subdirs as $subdir) {
        $ret = array_merge($ret, doot($subdir, $currentFreeSpace, $freeSpaceNeeded));
    }

    return $ret;
}

$root = new Dir('/');
$shell = new Shell();
$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/day07.txt'))));

foreach ($input as $i) {
    if (str_starts_with($i, '$')) {
        // command
        if (substr($i, 2, 2) === 'cd') {
            $dirName = substr($i, 5);
            if ($dirName === '..') {
                $shell->currentDir = $shell->currentDir->parent;
            } elseif ($dirName === '/') {
                $shell->currentDir = $root;
            } else {
                $seen = array_filter($shell->currentDir->subdirs, fn(Dir $d): bool => $d->name === $dirName);
                $shell->currentDir = (count($seen) > 0) ? reset($seen) : new Dir($dirName, $shell->currentDir);
            }
        }
    } else {
        if (str_starts_with($i, 'dir')) {
            $dirName = substr($i, 4);
            $shell->currentDir->subdirs[] = new Dir($dirName, $shell->currentDir);
        } else {
            [$size, $fileName] = explode(' ', $i);
            $shell->currentDir->files[] = new File($fileName, intval($size));
        }
    }
}

$dirs = doot($root, (70000000 - $root->size()), 30000000);
usort($dirs, function (array $a, array $b): int {
    return $a[1] <=> $b[1];
});

foreach ($dirs as $d) {
    echo $d[0]->name . PHP_EOL;
}

echo $dirs[0][1];