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
        return new ReaderRowCollection($this->reader, $this->pointersToUniques);
    }

    private function processAllInputRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $this->processRow($rowIndex);
        }
    }

    private function processRow($rowIndex){
        $row = $this->readRow($rowIndex);

        if ($this->isDuplicate($row)) {
            /*$this->copyFromUniquesToDuplicates($row);*/
            $this->removeUnique($row);

            /*$this->writeDuplicate($row);*/
        } else {
            $this->addUnique($row, $rowIndex);
        }
    }

    private function readRow($rowIndex){
        $row = new Row();
        $row->setHashCalculator($this->calculator);

        $data = $this->reader->readRow($rowIndex);
        $row->setData($data);

        return $row;
    }

    private function isDuplicate(Row $row){
        return $this->appearedRows->contains($row->getHash());
    }

/*    private function copyFromUniquesToDuplicates(Row $row){
        $rowIndex = &$this->pointersToUniques[$row->getHash()];
        if (isset($rowIndex)) {
            $pointedRow = new Row();
            $pointedRow->setHashCalculator($this->hashCalculator);

            $pointedRowData = $this->readRowData($rowIndex);
            $pointedRow->setData($pointedRowData);

            $this->writeDuplicate($pointedRow);
        }
    }*/

/*    private function writeDuplicate(Row $row){
        $this->getDuplicatesWriter($row)->writeRow($row->getData());
    }*/

/*    private function getDuplicatesWriter(Row $row) {
        if (!isset($this->duplicatesWriters[$row->getHash()])) {
            $this->duplicatesWriters[$row->getHash()] = $this->duplicatesWriterFactory->createWriter($row->getHash());
        }
        return $this->duplicatesWriters[$row->getHash()];
    }*/

    private function removeUnique(Row $row){
        unset($this->pointersToUniques[$row->getHash()]);
    }

    private function addUnique(Row $row, $rowIndex) {
        $this->appearedRows->add($row->getHash());
        $this->pointersToUniques[$row->getHash()] = $rowIndex;
    }

/*    private function writeUniques(){
        foreach ($this->pointersToUniques as $rowIndex){
            $row = $this->readRowData($rowIndex);
            $this->uniqueWriter->writeRow($row);
        }
    }*/
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