<?php

include_once("Readers/Reader.php");
include_once("Comparators/Comparator.php");

class DuplicatesScanner {
    private $reader, $columnComparator;
    private $uniqueRows = array(), $duplicateRows = array();

    function setReader(Reader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }

        $this->reader = $reader;
    }

    function setColumnComparator(Comparator $columnComparator){
        $this->columnComparator = $columnComparator;
    }

    function getUniques(){
        $this->scan();
        return array_values($this->uniqueRows);
    }

    private function scan(){
        while (!$this->reader->isEof()){
            $row = $this->reader->readRow();
            $hash = $this->getHash($row);

            if ($this->isDuplicate($hash)){
                $this->duplicateRows[] = $row;
            } else {
                $this->uniqueRows[$hash] = $row;
            }
        }
    }

    private function isDuplicate($hash){
        return isset($uniqueRows[$hash]);
    }

    private function getHash($row){
        return $this->getStringEquivalent($row);
    }

    private function getStringEquivalent($row){
        $string = "";
        foreach ($row as $key => $value){
            $string .= "$key$value";
        }

        return $string;
    }
}