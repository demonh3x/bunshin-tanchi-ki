<?php

include_once("Writer.php");

class CsvWriter implements Writer{
    private $ready = false,
        $fp;

    function create($path) {
        $this->fp = fopen($path, 'c');
    }

    function isReady() {
        return $this->ready;
    }

    function writeRow($data) {
        foreach ($data as $fields) {
            fputcsv($this->fp, $fields, ',', '"');
        }
    }

    function __destruct() {
        fclose($this->fp);
    }
}