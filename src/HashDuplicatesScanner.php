<?php

include_once("RandomReaders/RandomReader.php");
include_once("HashCalculators/HashCalculator.php");

include_once("Writers/Writer.php");
include_once("Writers/WriterFactory.php");

include_once("HashList.php");

class HashDuplicatesScanner {
    private $reader, $hashCalculator;
    private $uniqueWriter, $duplicatesWriterFactory;

    private $appearedRows;
    private $duplicatedWriters = array();
    private $uniqueRowIndexes = array();

    function __construct(){
        $this->appearedRows = new HashList();
    }

    function setReader(RandomReader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }
        $this->reader = $reader;
    }

    function setHashCalculator(HashCalculator $calculator){
        $this->hashCalculator = $calculator;
    }

    function setUniquesWriter(Writer $writer){
        if (!$writer->isReady()){
            throw new Exception("The unique writer is not ready!");
        }
        $this->uniqueWriter = $writer;
    }

    function scan(){
        if (!isset($this->hashCalculator)){
            throw new Exception("No hash calculator has been set!");
        }

        $this->processAllInputRows();
        $this->writeUniques();
    }

    private function processAllInputRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $this->processRow($rowIndex);
        }
    }

    private function processRow($rowIndex){
        $row = $this->reader->readRow($rowIndex);
        $rowHash = $this->getHash($row);

        if ($this->isDuplicate($rowHash)) {
            $this->removeUnique($rowHash);
        } else {
            $this->addUnique($rowHash, $rowIndex);
        }
    }

    private function getHash($row){
        return $this->hashCalculator->calculate($row);
    }

    private function isDuplicate($rowHash){
        return $this->appearedRows->contains($rowHash);
    }

    private function removeUnique($rowHash){
        unset($this->uniqueRowIndexes[$rowHash]);
    }

    private function addUnique($rowHash, $rowIndex) {
        $this->appearedRows->add($rowHash);
        $this->uniqueRowIndexes[$rowHash] = $rowIndex;
    }

    private function writeUniques(){
        foreach ($this->uniqueRowIndexes as $rowIndex){
            $row = $this->reader->readRow($rowIndex);
            $this->uniqueWriter->writeRow($row);
        }
    }
}