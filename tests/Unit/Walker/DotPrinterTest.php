<?php

namespace DTL\PhpDiagram\Tests\Unit\Walker;

use DTL\PhpDiagram\Ast\Graph;
use DTL\PhpDiagram\Parser\MermaidParser;
use DTL\PhpDiagram\Walker\DotPrinter;
use Generator;
use PHPUnit\Framework\TestCase;

class DotPrinterTest extends TestCase
{
    /**
     * @dataProvider providePrint
     */
    public function testPrint(array $lines, array $expected): void
    {
        self::assertEquals(
                implode("\n", $expected),
                (new DotPrinter())->convert((new MermaidParser())->parse(implode("\n", $lines)))
            );
    }
        
    /**
     * @return Generator<mixed>
     */
    public function providePrint(): Generator
    {
        yield [
                [
                    'graph TD',
                ],
                [
                    'digraph G {',
                    '}',
                ]
            ];

        yield [
                [
                    'graph TD',
                    'foobar --> barfoo'
                ],
                [
                    'digraph G {',
                    '  foobar -> barfoo;',
                    '}',
                ]
            ];

        yield [
                [
                    'graph TD',
                    'foobar[Barfoo] --> barfoo'
                ],
                [
                    'digraph G {',
                    '  foobar [ label = "Barfoo" ];',
                    '  foobar -> barfoo;',
                    '}',
                ]
            ];

        yield [
                [
                    'graph TD',
                    'foobar -->|My Label| barfoo'
                ],
                [
                    'digraph G {',
                    '  foobar -> barfoo [ label = "My Label" ];',
                    '}',
                ]
            ];

        yield [
                [
                    'graph TD',
                    'foobar --> barfoo'
                ],
                [
                    'digraph G {',
                    '  foobar -> barfoo [ label = "My Label" ];',
                    '}',
                ]
            ];
    }
}
