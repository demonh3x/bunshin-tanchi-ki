<?php

include_once("Readers/Reader.php");
include_once("HashList.php");

class HashDuplicatesScanner {
    private $reader;

    private $appearedRows, $uniqueRows = array();

    function __construct(){
        $this->appearedRows = new HashList();
    }

    function setReader(Reader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }

        $this->reader = $reader;
    }

    function getUniques(){
        while(!$this->reader->isEof()){
            $row = $this->reader->readRow();
            $this->check($row);
        }
        return array_values($this->uniqueRows);
    }

    private function check($row){
        $hash = $this->getHash($row);

        if ($this->appearedRows->contains($hash)) {
            $this->removeNotUniqueRow($hash);
        } else {
            $this->addUniqueRow($row, $hash);
        }
    }

    private function getHash($row){
        $hash = "";
        foreach ($row as $column => $value){
            $hash .= "$column$value";
        }
        return $hash;
    }

    private function removeNotUniqueRow($hash){
        unset($this->uniqueRows[$hash]);
    }

    private function addUniqueRow($row, $hash){
        $this->appearedRows->add($hash);
        $this->uniqueRows[$hash] = $row;
    }
}