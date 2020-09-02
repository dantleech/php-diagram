<?php

namespace DTL\PhpDiagram\Parser;

use DTL\PhpDiagram\Ast\Graph;
use DTL\PhpDiagram\Ast\AstNode;
use DTL\PhpDiagram\Ast\LeftToRight;
use DTL\PhpDiagram\Ast\Node;
use DTL\PhpDiagram\Ast\RectangleNode;
use DTL\PhpDiagram\Ast\RhombusNode;
use Verraes\Parsica\Parser;
use function Verraes\Parsica\alphaChar;
use function Verraes\Parsica\alphaNumChar;
use function Verraes\Parsica\atLeastOne;
use function Verraes\Parsica\between;
use function Verraes\Parsica\char;
use function Verraes\Parsica\choice;
use function Verraes\Parsica\collect;
use function Verraes\Parsica\eof;
use function Verraes\Parsica\eol;
use function Verraes\Parsica\many;
use function Verraes\Parsica\map;
use function Verraes\Parsica\printChar;
use function Verraes\Parsica\punctuationChar;
use function Verraes\Parsica\string;
use function Verraes\Parsica\whitespace;
use function Verraes\Parsica\zeroOrMore;

class MermaidParser
{
    public function parse(string $text): AstNode
    {
        return collect(
            string('graph')
                ->followedBy(whitespace())
                ->followedBy(atLeastOne(alphaChar()))
                ->thenIgnore(eol()->or(eof())),

            many($this->statementParser())->map(function ($v) {
                return $v;
            }),

            eol()->or(eof())->optional()
        )->map(function (array $vars) {
            return new Graph(
                $vars[0],
                $vars[1]
            );
        })->tryString($text)->output();
    }

    private function statementParser(): Parser
    {
        return $this->leftRightParser();
    }

    private function leftRightParser(): Parser
    {
        return collect(
            $this->anyNodeParser()->thenIgnore(whitespace()->optional()->then(string('-->'))->then(whitespace()->optional())),
            $this->connectionLabel()->optional()->thenIgnore(whitespace()->optional()),
            $this->anyNodeParser()->thenIgnore(eol()->optional())
        )->map(fn (array $vars) => new LeftToRight($vars[0], $vars[2], $vars[1]));
    }

    private function anyNodeParser(): Parser
    {
        return choice(
            $this->nodeParser(char('['), char(']'))->map(fn (array $vars) => new RectangleNode(...$vars)),
            $this->nodeParser(char('{'), char('}'))->map(fn (array $vars) => new RhombusNode(...$vars)),
            $this->nodeName()->map(fn (string $name) => new RectangleNode($name)),
        );
    }

    private function nodeParser(Parser $open, Parser $close): Parser
    {
        return collect(
            $this->nodeName(),
            between(
                $open,
                $close,
                $this->labelText()
            ),
        );
    }

    private function connectionLabel(): Parser
    {
        return between(
            char('|'), 
            char('|'), 
            $this->labelText()
        );
    }

    private function labelText(): Parser
    {
        return atLeastOne(alphaNumChar()->or(whitespace())->or(char('\'')));
    }

    private function nodeName(): Parser
    {
        return atLeastOne(alphaChar());
    }

}
