<?php

namespace Staticco\NodeVisitor;

use PhpParser\Node;

class CakeImportFinder implements \PhpParser\NodeVisitor
{
    private $static_calls = array();

    public function resetState()
    {
        $this->static_calls   = array();
    }

    /**
     * beforeTraverse
     *
     * @param array $nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $this->resetState();
    }

    /**
     * enterNode
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {

        if (
            $node instanceof Node\Expr\StaticCall
            && $node->class->toString() === 'App'
            && $node->name === 'import'
        ) {
            $this->static_calls[] = $node;
        }

        if (
            $node instanceof Node\Expr\StaticCall
            && $node->class->toString() === 'ClassRegistry'
            && $node->name === 'init'
        ) {
            $this->static_calls[] = $node;
        }
    }

    /**
     * afterTraverse
     *
     * @param array $nodes
     */
    public function afterTraverse(array $nodes)
    {
        // Print table of Cake-style imports
        echo PHP_EOL, 'Cake imports', PHP_EOL;
        printf(
            '  %4.4s | %s',
            'line',
            'name'
        );
        echo PHP_EOL;

        foreach($this->static_calls as $static_call) {
            $arg_values = array();
            foreach($static_call->args as $arg) {
                $arg_values[] = $arg->value->value;
            }
            printf(
                '  %4.4d | %s:%s(%s)',
                $static_call->getLine(),
                $static_call->class->toString(),
                $static_call->name,
                implode(', ', $arg_values)
                //implode('\\', $name_node->parts)
            );
            echo PHP_EOL;
        }

        if(count($this->static_calls) > 0) {
            return;
        }
    }

}
