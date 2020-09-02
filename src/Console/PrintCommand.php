<?php

namespace DTL\PhpDiagram\Console;

use DTL\PhpDiagram\Ast\AstNode;
use DTL\PhpDiagram\Parser\MermaidParser;
use DTL\PhpDiagram\Walker\DotPrinter;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCommand extends Command
{
    private MermaidParser $parser;
    private DotPrinter $printer;

    public function __construct(MermaidParser $parser, DotPrinter $printer)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->printer = $printer;
    }

    protected function configure(): void
    {
        $this->setName('print');
        $this->addArgument('file', InputArgument::REQUIRED, 'Source file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->readContents($input->getArgument('file'));
        $node = $this->parser->parse($contents);
        $output->write($this->printer->convert($node), false, OutputInterface::OUTPUT_RAW);
        return 0;
    }

    /**
     * @param mixed $path
     */
    private function readContents($path): string
    {
        if (!is_string($path)) {
            throw new RuntimeException(
                'Unexpected input'
            );
        }

        if (!file_exists($path)) {
            throw new RuntimeException(sprintf(
                'File "%s" does not exist',
$path
            ));
        }

        return (string)file_get_contents($path);
    }
}
