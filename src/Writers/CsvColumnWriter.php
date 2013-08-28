<?php

include_once("CsvWriter.php");

include_once("WriterException.php");

class CsvColumnWriter extends CsvWriter{
    private $areColumnsWritten = false;
    private $columnsOrder = array();
    private $filePath;
    private $notDefinedValue = "";

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
        fclose($fp);
    }

    private function sort($data){
        $defaultArray = array_flip($this->columnsOrder);
        foreach ($defaultArray as $key => $value){
            $defaultArray[$key] = $this->notDefinedValue;
        }
        $return = array_merge($defaultArray, $data);

        if (count($return) !== count($this->columnsOrder)){
            throw new WriterException("The row contains columns not defined in the file! " . print_r($data, true));
        }

        return $return;
    }
}