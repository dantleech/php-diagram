<?php

namespace DTL\PhpDiagram\Console;

use DTL\PhpDiagram\Ast\AstNode;
use DTL\PhpDiagram\Parser\MermaidParser;
use DTL\PhpDiagram\Renderer;
use DTL\PhpDiagram\Walker\DotPrinter;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCommand extends Command
{
    private Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
    }

    protected function configure(): void
    {
        $this->setName('print');
        $this->addArgument('file', InputArgument::REQUIRED, 'Source file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(
            $this->renderer->render(
                $this->readContents($input->getArgument('file'))
            ),
            false,
            OutputInterface::OUTPUT_RAW
        );
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
