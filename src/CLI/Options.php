<?php

namespace Staticco\CLI;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use UnexpectedValueException;

/**
 * Class: Options
 *
 */
class Options
{
    /**
     * @var mixed
     */
    public $file_paths = array();

    /**
     * @var string
     */
    public $output_dir = './out';

    /**
     * @var array
     */
    public $file_extensions = array('php' => true);

    /**
     * @var bool
     */
    public $follow_links = false;

    /**
     * @var bool
     */
    public $recursive = false;

    /**
     * @var int
     */
    public $concurrency = 1;

    /**
     * createFromArgsList
     *
     * @return self
     * @throws UnexpectedValueException
     */
    public static function createFromArgsList()
    {
        $instance = new self();
        $instance->parse(getopt('', $instance->getSupportedOptions()));
        return $instance;
    } 

    /**
     * getSupportedOptions
     *
     * @return array
     */
    public function getSupportedOptions()
    {
        return array(
            'file:',
            'dir:',
            'output:',
            'follow_links',
            'extension:',
            'recursive',
            'concurrency:',
        );
    }

    /**
     * parse
     *
     * @param array $opts
     * @throws UnexpectedValueException
     */
    public function parse(array $opts)
    {
        if (isset($opts['file'])) {
            $file_paths = (array)$opts['file'];
            foreach($file_paths as $file_path) {
                $this->file_paths[] = new SplFileInfo($file_path);
            }
        }

        if(isset($opts['recursive'])) {
            $this->recursive = true;
        }

        if (isset($opts['dir'])) {
            if ($this->recursive) {
                $this->file_paths = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($opts['dir'], FilesystemIterator::SKIP_DOTS)
                );
            }
            else {
                $this->file_paths = new RecursiveDirectoryIterator($opts['dir'], FilesystemIterator::SKIP_DOTS);
            }
        }

        if (isset($opts['output'])) {
            $this->output_dir = $opts['output'];
        }

        if (isset($opts['follow_links'])) {
            $this->follow_links = true;
        }

        if (isset($opts['extension'])) {
            $this->file_extensions = array_fill_keys(
                (array)$opts['extension'],
                true
            );
        }

        if (isset($opts['concurrency'])) {
            $this->concurrency = filter_var($opts['concurrency'], FILTER_VALIDATE_INT);
            if($this->concurrency === false) {
                throw new UnexpectedValueException('Unexepected value for concurrency option');
            }
        }
    }
}
