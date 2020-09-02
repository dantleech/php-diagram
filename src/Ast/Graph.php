<?php

namespace DTL\PhpDiagram\Ast;

use DTL\PhpDiagram\Ast\AstNode;

class Graph implements AstNode
{
    private string $orientation;
    private array $statements;

    /**
     * @param array<Statement> $statement
     */
    public function __construct(string $orientation, array $statements)
    {
        $this->orientation = $orientation;
        $this->statements = $statements;
    }
}
