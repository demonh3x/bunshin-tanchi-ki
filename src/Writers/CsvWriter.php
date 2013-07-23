<?php

include_once("Writer.php");

class CsvWriter implements Writer{
    private $ready = false,
        $fp;

    function create($path) {
        $this->fp = fopen($path, 'a');
        if ((bool) $this->fp)
        {
            $this->ready=true;
        }
    }

    function isReady() {
        return $this->ready;
    }

    function writeRow($data) {
        //TODO: Make a test to check the encoding if correctly happening.
        foreach ($data as $col => $val){
            if (mb_detect_encoding($val) != "UTF-8"){
                $data[$col] = utf8_encode($val);
            }
        }

        fputcsv($this->fp, $data);
    }

    function __destruct() {
        if ($this->fp)
        {
            fclose($this->fp);
        }
    }
}