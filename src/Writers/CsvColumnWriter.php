<?php

include_once("CsvWriter.php");
class CsvColumnWriter extends CsvWriter{
    private $areColumnsWritten = false;
    private $columnsOrder = array();

    function __construct($filePath){
        $this->areColumnsWritten = file_exists($filePath);
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

    private function sort($data){
        return array_merge(array_flip($this->columnsOrder), $data);
    }
}