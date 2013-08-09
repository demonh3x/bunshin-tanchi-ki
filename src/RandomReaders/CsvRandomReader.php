<?php

ini_set("auto_detect_line_endings", true);
include_once("RandomReader.php");

include_once("RandomReaderException.php");

class CsvRandomReader implements RandomReader{
    protected $filePointer;
    protected  $rowCount;
    protected $rowPositionMap = array();

    function __construct($filePath) {
        $this->checkIfPathIsValid($filePath);

        $this->filePointer = fopen($filePath, "r");

        $this->checkIfFileIsOpened($filePath);

        $this->scanFile();
    }

    protected function checkIfPathIsValid($path){
        if (empty($path)){
            throw new RandomReaderException("The path \"$path\" has to be valid!", 200);
        }

        if (!is_file($path)){
            throw new RandomReaderException("The path: \"$path\" doesn't represent a file!", 201);
        }
    }

    protected function checkIfFileIsOpened($path) {
        if (!$this->filePointer){
            throw new RandomReaderException("Can't open the file in the path: \"$path\"!", 299);
        }
    }

    protected function scanFile(){
        $position = $this->getFilePosition();
        $this->setFilePosition(0);

        $this->countRowsAndCreateMap();

        $this->setFilePosition($position);
    }

    protected function countRowsAndCreateMap(){
        $this->rowCount = 0;
        $this->rowPositionMap[$this->rowCount] = $this->getFilePosition();

        while(!$this->isEof()){
            $line = $this->readFileLine();
            if (!empty($line)){
                $this->rowCount++;
                $this->rowPositionMap[$this->rowCount] = $this->getFilePosition();
            }
        }
    }

    private function getFilePosition(){
        return ftell($this->filePointer);
    }

    protected function setFilePosition($position){
        fseek($this->filePointer, $position, SEEK_SET);
    }

    private function isEof(){
        return feof($this->filePointer);
    }

    protected function readFileLine(){
        return fgets($this->filePointer);
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