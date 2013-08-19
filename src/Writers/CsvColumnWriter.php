<?php

include_once("CsvWriter.php");
class CsvColumnWriter extends CsvWriter{
    private $areColumnsWritten = false;
    private $columnsOrder = array();
    private $filePath;

    function __construct($filePath){
        $this->filePath = $filePath;
        $this->areColumnsWritten = file_exists($filePath);

        if ($this->areColumnsWritten) {
            $this->readColumns();
        }

        parent::__construct($filePath);
    }

    function writeRow($data){
        if (!$this->areColumnsWritten) {
            $this->writeColumns($data);
        }

        parent::writeRow($this->sort($data));
    }

    private function writeColumns($data){
        $this->columnsOrder = array_keys($data);
        parent::writeRow($this->columnsOrder);
        $this->areColumnsWritten = true;
    }

    private function readColumns(){
        $fp = fopen($this->filePath, "r");
        $this->columnsOrder = array_values(fgetcsv($fp));
    }

    private function sort($data){
        return array_merge(array_flip($this->columnsOrder), $data);
    }
}