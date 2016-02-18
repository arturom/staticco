<?php

namespace Staticco;

use PhpParser\ParserAbstract;
use PhpParser\NodeTraverserInterface;
use PhpParser\PrettyPrinterAbstract;


class Worker
{
    /**
     * @var ParserAbstract
     */
    private $parser;

    /**
     * @var NodeTraverserInterface
     */
    private $traverser;

    /*
     * @var PrettyPrinterAbstract
     */
    private $pretty_printer;

    public function __construct(ParserAbstract $parser, NodeTraverserInterface $traverser, PrettyPrinterAbstract $pretty_printer)
    {
        $this->parser         = $parser;
        $this->traverser      = $traverser;
        $this->pretty_printer = $pretty_printer;
    }

    public function processFile($file_path)
    {
        $stmts = $this->parser->parse(file_get_contents($file_path));
        $stmts = $this->traverser->traverse($stmts);

        // echo $this->pretty_printer->prettyPrintFile($stmts);
        echo PHP_EOL, PHP_EOL, PHP_EOL;
    }
}
