<?php

include_once("Writer.php");

class CsvWriter implements Writer{
    private $ready = false,
        $fp;

    function create($path) {
        // TODO: Implement create() method.
        $this->fp = fopen($path.'file.csv', 'w');

    }

    function isReady() {
        // TODO: Implement isReady() method.

        return $this->ready;
    }

    function writeRow($data) {
        // TODO: Implement writeRow() method.
        foreach ($data as $fields) {
            fputcsv($this->fp, $fields, ',', '"');
        }
    }
}