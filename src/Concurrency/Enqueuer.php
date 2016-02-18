<?php

namespace Staticco\Concurrency;

use SplFileInfo;

/**
 * Class: Enqueuer
 *
 */
interface Enqueuer {

    /**
     * enqueueFile
     *
     * @param SplFileInfo $file_info
     */
    public function enqueueFile(SplFileInfo $file_info);

}
