<?php

namespace DTL\PhpDiagram\Tests\Unit\Parser;

use DTL\PhpDiagram\Ast\LeftToRight;
use DTL\PhpDiagram\Ast\Graph;
use DTL\PhpDiagram\Ast\AstNode;
use DTL\PhpDiagram\Ast\RectangleNode;
use DTL\PhpDiagram\Ast\RhombusNode;
use DTL\PhpDiagram\Parser\MermaidParser;
use Generator;
use PHPUnit\Framework\TestCase;

class MermaidParserTest extends TestCase
{
    /**
     * @dataProvider provideParse
     * @dataProvider provideNodes
     * @dataProvider provideLabels
     * @dataProvider provideConnectionLabels
     */
    public function testParse(array $lines, AstNode $expected): void
    {
        self::assertEquals($expected, (new MermaidParser())->parse(implode("\n", $lines)));
    }

    /**
     * @return Generator<mixed>
     */
    public function provideParse(): Generator
    {
        yield [
            [
                'graph TD',
            ],
            new Graph('TD', [])
        ];

        yield 'left to right' => [
            [
                'graph TD',
                'foobar --> barfoo',
                'barfoo--> foobar',
            ],
            new Graph('TD', [
                new LeftToRight(
                    new RectangleNode('foobar'),
                    new RectangleNode('barfoo')
                ),
                new LeftToRight(
                    new RectangleNode('barfoo'),
                    new RectangleNode('foobar'),
                )
            ])
        ];
    }

    /**
     * @return Generator<mixed>
     */
    public function provideLabels(): Generator
    {
        yield 'label 1' => [
            [
                'graph TD',
                'foobar[Foobar] --> barfoo',
            ],
            new Graph('TD', [
                new LeftToRight(
                    new RectangleNode('foobar', 'Foobar'),
                    new RectangleNode('barfoo')
                ),
            ])
        ];

        yield 'label 2' => [
            [
                'graph TD',
                'foobar[Foobar\'s Bar] --> barfoo',
            ],
            new Graph('TD', [
                new LeftToRight(
                    new RectangleNode('foobar', 'Foobar\'s Bar'),
                    new RectangleNode('barfoo')
                ),
            ])
        ];
    }

    /**
     * @return Generator<mixed>
     */
    public function provideConnectionLabels(): Generator
    {
        yield 'connection label' => [
            [
                'graph TD',
                'foobar -->|Foo Bar| barfoo',
            ],
            new Graph('TD', [
                new LeftToRight(
                    new RectangleNode('foobar'),
                    new RectangleNode('barfoo'),
                    'Foo Bar',
                ),
            ])
        ];
    }

    /**
     * @return Generator<mixed>
     */
    public function provideNodes(): Generator
    {
        yield [
            [
                'graph TD',
                'foobar{Bar foo} --> barfoo',
            ],
            new Graph('TD', [
                new LeftToRight(
                    new RhombusNode('foobar', 'Bar foo'),
                    new RectangleNode('barfoo')
                ),
            ])
        ];
    }
}
