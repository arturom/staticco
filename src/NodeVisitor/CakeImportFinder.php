<?php

namespace Staticco\NodeVisitor;

use PhpParser\Node;

class CakeImportFinder implements \PhpParser\NodeVisitor
{
    private $static_calls = array();
    private $method_calls = array();

    public function resetState()
    {
        $this->static_calls = array();
        $this->method_calls = array();
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
        if (
            $node instanceof Node\Expr\MethodCall
            && $node->name === 'loadModel'
        ) {
            $this->method_calls[] = $node;
        }
    }

    /**
     * leaveNode
     *
     * @param Node $node
     */
    public function leaveNode(Node $node)
    {}

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
                if($arg->value instanceof Node\Expr\Array_) {
                    $array_values = array();
                    foreach($arg->value->items as $array_item) {
                        if($array_item->value instanceof Node\Expr\BinaryOp\Concat) {
                            $array_values[] = $array_item->value->getType();
                            continue;
                        }
                        if(!isset($array_item->value->value)) {
                            var_dump($array_item);
                            exit;
                        }
                        $array_values[] = $array_item->value->value;
                    }
                    $arg_values[] = sprintf('array(%s)', implode(', ', $array_values));
                    continue;
                }
                if($arg->value instanceof Node\Expr\Variable) {
                    $arg_values[] = sprintf('$%s', $arg->value->name);
                    continue;
                }
                if($arg->value instanceof Node\Expr\ConstFetch) {
                    $arg_values[] =$arg->value->name->toString();
                    continue;
                }
                if(empty($arg->value->value)) {
                    var_dump($arg);
                    exit;
                }
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

        foreach($this->method_calls as $method_call) {
            $arg_values = array();
            foreach($method_call->args as $arg) {
                if($arg->value instanceof Node\Expr\Array_) {
                    $array_values = array();
                    foreach($arg->value->items as $array_item) {
                        if($array_item->value instanceof Node\Expr\BinaryOp\Concat) {
                            $array_values[] = $array_item->value->getType();
                            continue;
                        }
                        $array_values[] = $array_item->value->value;
                    }
                    $arg_values[] = sprintf('array(%s)', implode(', ', $array_values));
                    continue;
                }
                if($arg->value instanceof Node\Expr\Variable) {
                    $arg_values[] = sprintf('$%s', $arg->value->name);
                    continue;
                }
                if($arg->value instanceof Node\Expr\ConstFetch) {
                    $arg_values[] =$arg->value->name->toString();
                    continue;
                }
                if(empty($arg->value->value)) {
                    var_dump($arg);
                    exit;
                }
                $arg_values[] = $arg->value->value;
            }
            printf(
                '  %4.4d | $%s:%s(%s)',
                $method_call->getLine(),
                $method_call->var->name,
                $method_call->name,
                implode(', ', $arg_values)
            );
            echo PHP_EOL;
        }
    }

}
