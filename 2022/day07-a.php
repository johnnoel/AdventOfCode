<?php

class Dir {
    /** @var array<Dir> */
    public array $subdirs = [];
    /** @var array<File> */
    public array $files = [];

    public function __construct(public readonly string $name, public readonly ?Dir $parent = null)
    {
    }

    public function totalFileSize(): int
    {
        return array_sum(array_map(fn (File $f): int => $f->size, $this->files));
    }

    public function size(): int
    {
        return array_sum(array_map(fn (Dir $d): int => $d->size(), $this->subdirs)) + $this->totalFileSize();
    }
}

class File {
    public function __construct(public readonly string $name, public readonly int $size)
    {
    }
}

class Shell {
    public ?Dir $currentDir = null;
}

function fileTree(Dir $d, int $indent = 0) {
    foreach ($d->subdirs as $subdir) {
        echo str_repeat(' ', $indent) . $subdir->name . PHP_EOL;
        fileTree($subdir, $indent + 1);
    }

    foreach ($d->files as $file) {
        echo str_repeat(' ', $indent) . $file->name . ' (' . $file->size . ')' . PHP_EOL;
    }
}

function filterTotalSize(Dir $d, int $targetSize): array
{
    $ret = [];

    if (count($d->subdirs) > 0) {
        foreach ($d->subdirs as $dir) {
            if ($dir->size() <= $targetSize) {
                $ret[] = $dir;
            } else {
                $ret = array_merge($ret, filterTotalSize($dir, $targetSize));
            }
        }
    } else {
        if ($d->totalFileSize() <= $targetSize) {
            $ret[] = $d;
        }
    }

    return $ret;
}

$root = new Dir('/');
$shell = new Shell();
$input = array_filter(explode("\n", trim(file_get_contents(__DIR__ . '/temp.txt'))));

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
                $seen = array_filter($shell->currentDir->subdirs, fn (Dir $d): bool => $d->name === $dirName);
                $shell->currentDir = (count($seen) > 0) ? reset($seen) : new Dir($dirName, $shell->currentDir);
            }
        }
    } else {
        if (str_starts_with($i, 'dir')) {
            $dirName = substr($i, 4);
            $shell->currentDir->subdirs[] = new Dir($dirName, $shell->currentDir);
        } else {
            [ $size, $fileName ] = explode(' ', $i);
            $shell->currentDir->files[] = new File($fileName, intval($size));
        }
    }
}


$filteredDirs = filterTotalSize($root, 100000);

foreach($filteredDirs as $d) {
    echo $d->name . PHP_EOL;
}

echo array_sum(array_map(fn (Dir $d): int => $d->size(), $filteredDirs));