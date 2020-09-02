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
use Symfony\Component\Process\Process;
use function Safe\tmpfile;

class GenerateCommand extends Command
{
    private Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
    }

    protected function configure(): void
    {
        $this->setName('generate');
        $this->addArgument('file', InputArgument::REQUIRED, 'Source file');
        $this->addArgument('out', InputArgument::REQUIRED, 'Out file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->readContents($input->getArgument('file'));
        $tmpFile = tempnam(sys_get_temp_dir(), 'phpdiagram');
        file_put_contents($tmpFile, $this->renderer->render($contents));
        $process = new Process([
            'dot',
            $tmpFile,
            '-Tpng',
            '-o',
            $input->getArgument('out')
        ]);
        $process->mustRun();

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
