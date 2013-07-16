<?php

include_once("HashCalculators/HashCalculator.php");
include_once("RandomReaders/RandomReader.php");

include_once("HashList.php");
include_once("Row.php");

include_once("RowCollection.php");

include_once("DuplicatesListener.php");

class HashUniquesScanner {
    private $calculator, $readers = array();

    private $appearedRows;
    private $uniqueRows = array();

    private $duplicatesListener;

    function __construct(HashCalculator $calculator){
        $this->calculator = $calculator;
        $this->appearedRows = new HashList();
        $this->setDuplicatesListener(new NullDuplicatesListener());
    }

    function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    function setDuplicatesListener(DuplicatesListener $listener){
        $this->duplicatesListener = $listener;
    }

    function getUniques(){
        $this->processAllInputRows();
        return $this->createResultsIterator();
    }

    private function processAllInputRows(){
        foreach ($this->readers as $reader){
            for ($rowIndex = 0; $rowIndex < $reader->getRowCount(); $rowIndex++) {
                $this->processRow($reader, $rowIndex);
            }
        }
    }

    private function processRow(RandomReader $reader, $rowIndex){
        $row = $this->readRow($reader, $rowIndex);

        if ($this->isDuplicate($row)) {
            $this->removeUniqueAndSendItAsDuplicate($row);
            $this->sendDuplicate($row);
        } else {
            $this->addUnique($row);
        }
    }

    private function readRow(RandomReader $reader, $rowIndex){
        $row = new Row($reader, $rowIndex);
        $row->setHashCalculator($this->calculator);

        return $row;
    }

    private function isDuplicate(Row $row){
        return $this->appearedRows->contains($row->getHash());
    }

    private function removeUniqueAndSendItAsDuplicate(Row $row){
        if ($this->isInUniqueRows($row)){
            $this->sendUniqueAsDuplicate($row);
            $this->removeUnique($row);
        }
    }

    private function isInUniqueRows(Row $row){
        return isset($this->uniqueRows[$row->getHash()]);
    }

    private function sendUniqueAsDuplicate(Row $row){
        $this->sendDuplicate($this->uniqueRows[$row->getHash()]);
    }

    private function removeUnique(Row $row){
        unset($this->uniqueRows[$row->getHash()]);
    }

    private function addUnique(Row $row){
        $this->appearedRows->add($row->getHash());
        $this->uniqueRows[$row->getHash()] = $row;
    }

    private function createResultsIterator() {
        return new RowCollection($this->uniqueRows);
    }

    private function sendDuplicate(Row $row){
        $this->duplicatesListener->receiveDuplicate($row);
    }
}

class NullDuplicatesListener implements DuplicatesListener{
    function receiveDuplicate(Row $row){}
}