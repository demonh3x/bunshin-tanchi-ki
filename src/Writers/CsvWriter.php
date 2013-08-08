<?php

include_once("Writer.php");

class CsvWriter implements Writer{
    private $fp;

    function __construct($path) {
        $this->fp = fopen($path, 'a');

        //TODO: Make a test to check this exception.
        if (!$this->fp){
            throw new WriterException("Can't open the file: \"$path\"!");
        }
    }

    function writeRow($data) {
        //TODO: Make a test to check the encoding is correctly happening.
        foreach ($data as $col => $val){
            if (mb_detect_encoding($val) != "UTF-8"){
                $data[$col] = utf8_encode($val);
            }
        }

        fputcsv($this->fp, $data);
    }

    function __destruct() {
        if ($this->fp){
            fclose($this->fp);
        }
    }
}