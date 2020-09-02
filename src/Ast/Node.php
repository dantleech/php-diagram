<?php

namespace DTL\PhpDiagram\Ast;

class Node
{
    private string $name;

    private string $label;

    public function __construct(string $name, ?string $label = null)
    {
        $this->name = $name;
        $this->label = $label ?? $name;
    }
}
