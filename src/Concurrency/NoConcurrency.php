<?php

namespace Staticco\Concurrency;

use SplFileInfo;
use Staticco\Worker;

/**
 * Class: NoConcurrency
 *
 * @see Enqueuer
 */
class NoConcurrency implements Enqueuer
{

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * enqueueFile
     *
     * @param SplFileInfo $file_info
     */
    public function enqueueFile(SplFileInfo $file_info)
    {
        echo 'file: ', $file_info->getRealPath(), PHP_EOL;
        $this->worker->processFile($file_info->getRealPath());
    }
}
