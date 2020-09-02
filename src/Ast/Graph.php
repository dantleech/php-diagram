<?php

namespace DTL\PhpDiagram\Ast;

use DTL\PhpDiagram\Ast\AstNode;

class Graph implements AstNode
{
    private string $orientation;

    /**
     * @var array<Statement>
     */
    private array $statements;

    /**
     * @param array<Statement> $statements
     */
    public function __construct(string $orientation, array $statements)
    {
        $this->orientation = $orientation;
        $this->statements = $statements;
    }

    /**
     * @return array<Statement>
     */
    public function statements(): array
    {
        return $this->statements;
    }
}
