<?php

include_once("HashCalculators/HashCalculator.php");
include_once("RandomReaders/RandomReader.php");

include_once("HashList.php");
include_once("Row.php");

include_once("RowCollection.php");

class HashUniquesScanner {
    private $calculator, $readers = array();

    private $appearedRows;
    private $uniqueRows = array();

    function __construct(HashCalculator $calculator){
        $this->calculator = $calculator;
        $this->appearedRows = new HashList();
    }

    function addReader(RandomReader $reader){
        $this->readers[] = $reader;
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
        unset($this->uniqueRows[$row->getHash()]);
    }

    private function addUnique(Row $row){
        $this->appearedRows->add($row->getHash());
        $this->uniqueRows[$row->getHash()] = $row;
    }

    private function createResultsIterator() {
        return new RowCollection($this->uniqueRows);
    }
}