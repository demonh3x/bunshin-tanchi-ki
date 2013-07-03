<?php

class CsvReader {
    private $ready = false;
    private $fp;

    function open($path){
        $this->fp = fopen($path, "r");
        $this->ready = (bool) $this->fp;
    }

    function isReady(){
        return $this->ready;
    }

    function readRow(){
        $data = fgetcsv($this->fp, 1000, ",");
        return $data;
    }
}