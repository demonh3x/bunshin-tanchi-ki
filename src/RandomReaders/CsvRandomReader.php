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
        $this->moveFilePosition(0);

        $this->countRowsAndCreateMap();

        $this->moveFilePosition($position);
    }

    private function countRowsAndCreateMap(){
        $this->rowCount = 0;
        while(!$this->eof()){
            $this->rowPositionMap[] = $this->getFilePosition();

            $this->readFileLine();
            $this->rowCount++;
        }
    }

    private function getFilePosition(){
        return ftell($this->filePointer);
    }

    private function moveFilePosition($position){
        fseek($this->filePointer, $position, SEEK_SET);
    }

    private function eof(){
        return feof($this->filePointer);
    }

    private function readFileLine(){
        return fgets($this->filePointer);
    }

    function isReady() {
        return $this->ready;
    }

    function readRow($index) {
        $this->moveFilePosition($this->rowPositionMap[$index]);
        return str_getcsv(fgets($this->filePointer));
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