<?php

namespace DTL\PhpDiagram\Ast;

class Node implements AstNode
{
    private string $name;

    private ?string $label;

    public function __construct(string $name, ?string $label = null)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function label(): ?string
    {
        return $this->label;
    }
}
