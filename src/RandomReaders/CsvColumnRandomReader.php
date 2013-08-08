<?php

ini_set("auto_detect_line_endings", true);
include_once("RandomReader.php");

include_once("InputException.php");

class CsvColumnRandomReader implements RandomReader{
    private $filePointer;
    private $rowCount;
    private $rowPositionMap = array();
    private $columnNames = array();

    function __construct($path) {
        if (empty($path)){
            throw new InputException("The path has to be valid!", 2000);
        }

        if (!is_file($path)){
            throw new InputException("The path: \"$path\" doesn't represent a file!", 2001);
        }

        $this->filePointer = fopen($path, "r");

        if (!$this->filePointer){
            throw new InputException("Can't open \"$path\"", 2099);
        }

        $this->scanFile();
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