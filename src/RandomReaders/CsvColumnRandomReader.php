<?php

ini_set("auto_detect_line_endings", true);
include_once("RandomReader.php");

class CsvColumnRandomReader implements RandomReader{
    private $ready = false;
    private $filePointer;
    private $rowCount;
    private $rowPositionMap = array();
    private $columnNames = array();

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
        $this->readIndexesFromFirstRow();
    }

    private function countRowsAndCreateMap(){
        $this->rowCount = 0;
        $this->rowPositionMap[$this->rowCount] = $this->getFilePosition();

        while(!$this->isEof()){
            $line = $this->readFileLine();
            if (!empty($line)){
                $this->rowCount++;
                $this->rowPositionMap[$this->rowCount] = $this->getFilePosition();
            }
        }

        $this->rowCount -= $this->rowCount > 0? 1: 0;
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
        $this->setFilePosition($this->rowPositionMap[$index + 1]);
        $ret = $this->readFileLine();
        $ret = str_getcsv($ret);
        foreach ($ret as $i => $value)
        {
            $ret2[$this->columnNames[$i]] = $value;
        }
        return $ret2;
    }

    function getRowCount() {
        return $this->rowCount;
    }

    private function readIndexesFromFirstRow() {
        $this->setFilePosition($this->rowPositionMap[0]);
        $columnNames = $this->readFileLine();
        $columnNames = str_getcsv($columnNames);
        $this->columnNames = $columnNames;
    }

    function __destruct(){
        if ($this->filePointer){
            fclose($this->filePointer);
        }
    }
}