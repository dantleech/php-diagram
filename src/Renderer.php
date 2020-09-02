<?php

namespace DTL\PhpDiagram;

use DTL\PhpDiagram\Parser\MermaidParser;
use DTL\PhpDiagram\Walker\DotPrinter;

class Renderer
{
    private MermaidParser $parser;
    private DotPrinter $printer;

    public function __construct(MermaidParser $parser = null, DotPrinter $printer = null)
    {
        $this->parser = $parser ?? new MermaidParser();
        $this->printer = $printer ?? new DotPrinter();
    }

    public function render(string $contents): string
    {
        return $this->printer->print(
            $this->parser->parse($contents)
        );
    }
}
