<?php

ini_set('auto_detect_line_endings', TRUE);
include_once("Reader.php");

class CsvReader implements Reader{
    private $ready = false,
            $fp,
            $cachedRow,
            $eof = false;

    function open($path){
        $this->fp = fopen($path, "r");
        $this->ready = (bool) $this->fp;
        $this->cycleCachedRow();
    }

    function __destruct(){
        if ($this->fp){
            fclose($this->fp);
        }
    }

    function isReady(){
        return $this->ready;
    }

    function readRow(){
        return $this->cycleCachedRow();
    }

    private function cycleCachedRow(){
        $previousCache = $this->cachedRow;

        if ($this->isReady()){
            $data = fgetcsv($this->fp, 0, ",");
            $this->eof = !(bool) $data;
            $this->cachedRow = $data;
        }

        return $previousCache;
    }

    function isEof(){
        return $this->eof;
    }
}