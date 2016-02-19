#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$parser         = new PhpParser\Parser(new PhpParser\Lexer);
$traverser      = new PhpParser\NodeTraverser;
$pretty_printer = new PhpParser\PrettyPrinter\Standard;

$traverser->addVisitor(new PhpParser\NodeVisitor\NameResolver);
$traverser->addVisitor(new Staticco\NodeVisitor\Debugger);
$traverser->addVisitor(new Staticco\NodeVisitor\CakeImportFinder);

$worker = new Staticco\Worker($parser, $traverser, $pretty_printer);

$app      = new Staticco\CLI\App;
$enqueuer = new Staticco\Concurrency\NoConcurrency($worker);
$options  = Staticco\CLI\Options::createFromArgsList();

$app->perform($options, $enqueuer);
