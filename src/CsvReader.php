<?php

class CsvReader {
    private $ready = false;

    function openFile(){
        $this->ready = true;
    }

    function isReady(){
        return $this->ready;
    }

    function readRow(){
        return null;
    }
}