<?php

include_once("HashCalculators/HashCalculator.php");
include_once("RandomReaders/RandomReader.php");

include_once("HashList.php");
include_once("Row.php");

include_once("ReaderRowCollection.php");
include_once("IteratorGroup.php");

class HashUniquesScanner {
    private $calculator, $reader;

    private $appearedRows;

    private $pointersToUniques = array();

    function __construct(HashCalculator $calculator){
        $this->calculator = $calculator;
        $this->appearedRows = new HashList();
        $this->reader = new NullRandomReader();
    }

    function setReader(RandomReader $reader){
        $this->reader = $reader;
    }

    function getUniques(){
        $this->processAllInputRows();
        return $this->createResultsIterator();
    }

    private function processAllInputRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $this->processRow($this->reader, $rowIndex);
        }
    }

    private function processRow($reader, $rowIndex){
        $row = $this->readRow($reader, $rowIndex);

        if ($this->isDuplicate($row)) {
            /*$this->copyFromUniquesToDuplicates($row);*/
            $this->removeUnique($row);

            /*$this->writeDuplicate($row);*/
        } else {
            $this->addUnique($row);
        }
    }

    private function readRow($reader, $rowIndex){
        $row = new Row($reader, $rowIndex);
        $row->setHashCalculator($this->calculator);

        return $row;
    }

    private function isDuplicate(Row $row){
        return $this->appearedRows->contains($row->getHash());
    }

    private function removeUnique(Row $row){
        unset($this->pointersToUniques[$row->getHash()]);
    }

    private function addUnique(Row $row) {
        $this->appearedRows->add($row->getHash());
        $this->pointersToUniques[$row->getHash()] = $row->getIndex();
    }

    private function createResultsIterator() {
        return new ReaderRowCollection($this->reader, $this->pointersToUniques);
    }
}

class NullRandomReader implements RandomReader{
    function open($path){
    }

    function isReady(){
        return true;
    }

    function readRow($index){
        return array();
    }

    function getRowCount(){
        return 0;
    }
}