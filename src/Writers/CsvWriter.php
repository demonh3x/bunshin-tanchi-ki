<?php

include_once("Writer.php");

class CsvWriter implements Writer{
    private $ready = false,
        $fp;

    function create($path) {
        $this->fp = fopen($path, 'w');
        if ((bool) $this->fp)
        {
            $this->ready=true;
        }
    }

    function isReady() {
        return $this->ready;
    }

    function writeRow($data) {
        fputcsv($this->fp, $data);
    }

    function __destruct() {
        if ($this->fp)
        {
            fclose($this->fp);
        }
    }
}