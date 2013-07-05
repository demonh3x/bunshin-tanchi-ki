<?php

include_once("Readers/Reader.php");

class HashDuplicatesScanner {
    private $reader;

    function setReader(Reader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }

        $this->reader = $reader;
    }

    function getUniques(){
        $uniques = array();

        while(!$this->reader->isEof()){
            $row = $this->reader->readRow();
            $uniques[] = $row;
        }

        return $uniques;
    }
}