<?php

namespace DTL\PhpDiagram\Ast;

class LeftToRight implements Statement
{
    private Node $left;
    private Node $right;

    private ?string $label;

    public function __construct(Node $left, Node $right, ?string $label = null)
    {
        $this->left = $left;
        $this->right = $right;
        $this->label = $label;
    }

    public function left(): Node
    {
        return $this->left;
    }

    public function right(): Node
    {
        return $this->right;
    }

    public function label(): ?string
    {
        return $this->label;
    }
}
