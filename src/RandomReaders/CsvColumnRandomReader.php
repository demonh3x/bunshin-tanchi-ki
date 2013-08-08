<?php

ini_set("auto_detect_line_endings", true);
include_once("CsvRandomReader.php");

include_once("RandomReaderException.php");

class CsvColumnRandomReader extends CsvRandomReader{
    private $columnNames = array();

    protected function checkIfPathIsValid($path){
        if (empty($path)){
            throw new RandomReaderException("The path \"$path\" has to be valid!", 2000);
        }

        if (!is_file($path)){
            throw new RandomReaderException("The path: \"$path\" doesn't represent a file!", 2001);
        }
    }

    protected function scanFile(){
        parent::scanFile();
        $this->readIndexesFromFirstRow();
    }

    protected function countRowsAndCreateMap(){
        parent::countRowsAndCreateMap();
        $this->rowCount -= $this->rowCount > 0? 1: 0;
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

    private function readIndexesFromFirstRow() {
        $this->setFilePosition($this->rowPositionMap[0]);
        $columnNames = $this->readFileLine();
        $columnNames = str_getcsv($columnNames);
        $this->columnNames = $columnNames;
    }
}