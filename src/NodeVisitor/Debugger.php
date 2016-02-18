<?php

namespace Staticco\NodeVisitor;

use PhpParser\Node;

class Debugger implements \PhpParser\NodeVisitor
{
    private $unique_classes = array();

    private $name_nodes = array();

    private $declared_classes = array();

    private $tab_level = 0;

    public function resetState()
    {
        $this->unique_classes = array();
        $this->name_nodes     = array();
        $this->tab_level      = 0;
        $this->declared_classes = array();
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
        /*
        printf(
            '%-12.12s %4d %-40.40s %s',
            str_repeat('>', $this->tab_level++),
            $node->getLine(),
            get_class($node),
            json_encode(array_keys(get_object_vars($node)))
        );
        echo PHP_EOL;
         */

        $this->unique_classes[get_class($node)] = true;

        if ($node instanceof Node\Name\FullyQualified) {
            $this->name_nodes[] = $node;
        }

        if ($node instanceof Node\Stmt\Class_) {
            $this->declared_classes[] = $node;
        }
    }

    /**
     * leaveNode
     *
     * @param Node $node
     */
    public function leaveNode(Node $node)
    {
        $this->tab_level--;
    }

    /**
     * afterTraverse
     *
     * @param array $nodes
     */
    public function afterTraverse(array $nodes)
    {

        // Print table of declared classes
        echo PHP_EOL, 'Declared Classes', PHP_EOL;
        printf(
            '  %4.4s | %s',
            'line',
            'name'
        );
        echo PHP_EOL;

        foreach($this->declared_classes as $class) {

            printf(
                '  %4.4d | %s',
                $class->getLine(),
                $class->namespacedName->toString()
            );
            echo PHP_EOL;


            continue;
            $as_array = get_object_vars($class);
            unset($as_array['stmts']);
            print_r($as_array);
        }


        // Print table of name nodes
        echo PHP_EOL, 'Referenced names', PHP_EOL;
        printf(
            '  %4.4s | %s',
            'line',
            'name'
        );
        echo PHP_EOL;

        foreach($this->name_nodes as $name_node) {
            printf(
                '  %4.4d | %s',
                $name_node->getLine(),
                $name_node->toString()
                //implode('\\', $name_node->parts)
            );
            echo PHP_EOL;
        }
    }
}
