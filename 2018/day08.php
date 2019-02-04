<?php

$rawLicense = array_map('intval', explode(' ', file_get_contents(__DIR__.'/inputs/08.txt')));

class Node
{
    public $childCount = 0;
    public $metadataCount = 0;
    public $metadata = [];
    public $children = [];
}

function descend(array &$remaining)
{
    $node = new Node();
    $node->childCount = array_shift($remaining);
    $node->metadataCount = array_shift($remaining);

    if ($node->childCount > 0) {
        for ($i = 0; $i < $node->childCount; $i++) {
            $node->children[] = descend($remaining);
        }
    }

    if ($node->metadataCount > 0) {
        for ($i = 0; $i < $node->metadataCount; $i++) {
            $node->metadata[] = array_shift($remaining);
        }
    }

    return $node;
}

function logNode(Node $n, int $indent = 0)
{
    echo str_repeat(' ', $indent).spl_object_hash($n).PHP_EOL;
    for ($i = 0; $i < $n->childCount; $i++) {
        logNode($n->children[$i], $indent + 1);
    }
}

function countMetadata(Node $node) : int
{
    $childSum = 0;

    foreach ($node->children as $childNode) {
        $childSum += countMetadata($childNode);
    }

    $sum = array_sum($node->metadata);

    return $childSum + $sum;
}

$rootNode = descend($rawLicense);

var_dump(countMetadata($rootNode));
