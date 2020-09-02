<?php

namespace DTL\PhpDiagram\Walker;

use DTL\PhpDiagram\Ast\AstNode;
use DTL\PhpDiagram\Ast\Graph;
use DTL\PhpDiagram\Ast\LeftToRight;
use DTL\PhpDiagram\Ast\Node;
use DTL\PhpDiagram\Ast\RectangleNode;
use DTL\PhpDiagram\Ast\RhombusNode;
use DTL\PhpDiagram\Ast\Statement;
use RuntimeException;

class DotPrinter
{
    /**
     * @var array<string,mixed>
     */
    private $options;

    /**
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function print(AstNode $node): string
    {
        if ($node instanceof Graph) {
            return $this->convertGraph($node);
        }

        if ($node instanceof LeftToRight) {
            return $this->convertLeftToRight($node);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to walk node "%s"',
            get_class($node)
        ));
    }

    private function convertGraph(Graph $node): string
    {
        return implode("\n", array_filter([
            'digraph G {',
            implode("\n", array_map(
                fn (AstNode $node) => $this->renderNode($node),
                $this->nodesToDefine(...$node->statements())
            )),
            implode("\n", array_map(
                fn (string $name, string $value) => sprintf('  %s = %s;', $name, $value),
                array_keys($this->options),
                array_values($this->options),
            )),
            implode("\n", array_map(fn (AstNode $node) => $this->print($node), $node->statements())),
            '}']
        ));
    }

    private function convertLeftToRight(LeftToRight $node): string
    {
        $dot = sprintf('  %s -> %s', $node->left()->name(), $node->right()->name());

        if ($node->label()) {
            $dot = sprintf('%s [ label = "%s" ]', $dot, $node->label());
        }

        return sprintf('%s;', $dot);
    }

    /**
     * @return array<AstNode>
     */
    private function nodesToDefine(Statement ...$statements): array
    {
        $nodes = [];
        foreach ($statements as $statement) {
            if (!$statement instanceof LeftToRight) {
                continue;
            }

            if (!isset($nodes[$statement->left()->name()])) {
                $nodes[$statement->left()->name()] = $statement->left();
            }
            if (!isset($nodes[$statement->right()->name()])) {
                $nodes[$statement->right()->name()] = $statement->right();
            }
        }

        return array_filter($nodes, function (Node $node) {
            return $node->label() !== null;
        });
    }

    private function renderNode(AstNode $node): string
    {
        if ($node instanceof RectangleNode) {
            return $this->renderNodeShape($node, [
                'shape' => 'rect',
                'fillcolor' => '"#eeeeee"',
                'color' => '"#aaaaaa"',
                'style' => 'filled',
            ],  10);
        }
        if ($node instanceof RhombusNode) {
            return $this->renderNodeShape($node, [
                'shape' => 'diamond',
                'fillcolor' => '"#eeeeff"',
                'color' => '"#aaaaaa"',
                'style' => 'filled',
            ], 10);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to render node "%s"',
            get_class($node)
        ));
    }

    /**
     * @param array<string,string> $props
     */
    private function renderNodeShape(Node $node, array $props, int $wrapLength = null): string
    {
        if ($node->label()) {
            $props['label'] = $this->wrapLabel(sprintf('"%s"', $node->label()), $wrapLength);
        }

        $dot = $node->name();

        if ($props) {
            $dot .= sprintf(' [ %s ]', implode(', ' , array_map(function (string $name, string $value) {
                return sprintf('%s=%s', $name, $value);
            }, array_keys($props), array_values($props))));
        }

        return '  ' . $dot . ';';
    }

    private function wrapLabel(string $label, ?int $wrapLength): string
    {
        if (!$wrapLength) {
            return $label;
        }
        $count = 0;
        $return = [];
        $newLine = false;
        foreach ((array)mb_str_split($label) as $char) {
            $return[] = $char;
            if ($count++ > $wrapLength) {
                $newLine = true;
            }

            if ($newLine && $char === ' ') {
                $count = 0;
                $newLine = false;
                $return[] = "\n";
            }
        }

        return implode('', $return);
    }
}
