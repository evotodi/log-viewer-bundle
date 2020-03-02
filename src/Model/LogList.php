<?php

namespace Evotodi\LogViewerBundle\Model;


class LogList
{


    public function getLogList($logDirectory)
    {
        $logs = [];
        $files = scandir($logDirectory);
        //this will can two levels deep in our log folder for files
        // if we ever have logs more than two levels deep we should make this recursive.
        foreach ($files as $log) {
            $fileWithPath = $logDirectory . "/" . $log;
            if (is_file($fileWithPath) && $this->isLogFile($fileWithPath)) {
                $logs[] = $log;
            } elseif (is_dir($fileWithPath) && $log !== '.') {
                $nestFiles = scandir($fileWithPath);
                foreach ($nestFiles as $nestFile) {
                    $nestFileWithPath = $fileWithPath . "/" . $nestFile;
                    if (is_file($nestFileWithPath) && $this->isLogFile($nestFileWithPath)) {
                        $logs[] = $log . "/" . $nestFile;
                    }
                }
            }
        }
        return $logs;
    }

    private function isLogFile($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $ext === 'log';
    }
}