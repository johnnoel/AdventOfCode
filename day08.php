<?php

$rawLicense = array_map('intval', explode(' ', file_get_contents(__DIR__.'/inputs/08.txt')));

class Node
{
    public $childCount = 0;
    public $metadataCount = 0;
    public $metadata = [];
    public $children = [];

    public function getValue()
    {
        if ($this->childCount === 0) {
            return array_sum($this->metadata);
        }

        return array_sum(array_map(function(int $idx) {
            if ($idx === 0 || !array_key_exists($idx - 1, $this->children)) {
                return 0;
            }

            return $this->children[$idx - 1]->getValue();
        }, $this->metadata));
    }
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

var_dump($rootNode->getValue());
