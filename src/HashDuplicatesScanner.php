<?php

include_once("Readers/Reader.php");
include_once("HashList.php");
include_once("Comparators/Filters/FilterGroup.php");

class HashDuplicatesScanner {
    private $reader;

    private $appearedRows, $uniqueRows = array();
    private $duplicatedRows = array();

    private $filter;

    function __construct(){
        $this->appearedRows = new HashList();
        $this->filter = new FilterGroup();
    }

    function setReader(Reader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }

        $this->reader = $reader;
    }

    function getUniques(){
        $this->processInput();
        return array_values($this->uniqueRows);
    }

    private function processInput(){
        while(!$this->reader->isEof()){
            $row = $this->reader->readRow();
            $this->check($row);
        }
    }

    private function check($row){
        $hash = $this->getHash($row);

        if ($this->appearedRows->contains($hash)) {
            $this->moveUniqueToDuplicateRows($hash);
            $this->addDuplicateRow($row, $hash);
        } else {
            $this->addUniqueRow($row, $hash);
        }
    }

    private function getHash($row){
        $hash = "";
        foreach ($row as $column => $value){
            $value = $this->filter->filter($value);
            $hash .= "$column$value";
        }
        return $hash;
    }

    private function moveUniqueToDuplicateRows($hash){
        $row = $this->uniqueRows[$hash];
        $this->addDuplicateRow($row, $hash);
        unset($this->uniqueRows[$hash]);
    }

    private function addDuplicateRow($row, $hash){
        $this->duplicatedRows[$hash][] = $row;
    }

    private function addUniqueRow($row, $hash){
        $this->appearedRows->add($hash);
        $this->uniqueRows[$hash] = $row;
    }

    function getDuplicates(){
        $this->processInput();
        return array_values($this->duplicatedRows);
    }

    function setFilter(Filter $filter){
        $this->filter = $filter;
    }
}