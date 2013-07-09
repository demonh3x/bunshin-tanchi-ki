<?php

include_once("RandomReader.php");

class CsvRandomReader implements RandomReader{
    private $ready = false;
    private $filePointer;
    private $rowCount;
    private $rowPositionMap = array();

    function open($path) {
        $this->filePointer = fopen($path, "r");
        $this->ready = (bool) $this->filePointer;
        if ($this->isReady()){
            $this->scanFile();
        }
    }

    private function scanFile(){
        $position = $this->getFilePosition();
        $this->setFilePosition(0);

        $this->countRowsAndCreateMap();

        $this->setFilePosition($position);
    }

    private function countRowsAndCreateMap(){
        $this->rowCount = 0;
        while(!$this->isEof()){
            $this->rowPositionMap[$this->rowCount] = $this->getFilePosition();

            $this->readFileLine();
            $this->rowCount++;
        }
    }

    private function getFilePosition(){
        return ftell($this->filePointer);
    }

    private function setFilePosition($position){
        fseek($this->filePointer, $position, SEEK_SET);
    }

    private function isEof(){
        return feof($this->filePointer);
    }

    private function readFileLine(){
        return fgets($this->filePointer);
    }

    function isReady() {
        return $this->ready;
    }

    function readRow($index) {
        $this->setFilePosition($this->rowPositionMap[$index]);
        $ret = $this->readFileLine();
        $ret = str_getcsv($ret);
        return $ret;
    }

    function getRowCount() {
        return $this->rowCount;
    }

    function __destruct(){
        if ($this->filePointer){
            fclose($this->filePointer);
        }
    }
}