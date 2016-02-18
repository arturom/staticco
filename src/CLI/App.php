<?php

namespace Staticco\CLI;

use Staticco\Concurrency\Enqueuer;
use UnexpectedValueException;

class App
{
    public function perform(Options $opts, Enqueuer $enqueuer)
    {
        foreach ($opts->file_paths as $file_info) {
            if($file_info->isLink() && !$opts->follow_links) {
                continue;
            }
            if ($file_info->isDir() ) {
                continue;
            }

            if(!isset($opts->file_extensions[$file_info->getExtension()])) {
                continue;
            }

            if (!$file_info->isReadable()) {
                throw new UnexpectedValueException('File is not readable');
            }

            if(!$file_info->isFile()) {
                print_r($file_info);
                throw new UnexpectedValueException('Invalid file system entry in array');
            }

            $enqueuer->enqueueFile($file_info);
        }
    }
}
