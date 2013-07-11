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
    private $duplicatesWriters = array();
    private $uniqueRowIndexes = array();

    function __construct(){
        $this->appearedRows = new HashList();
        $this->uniqueWriter = new NullWriter();
        $this->duplicatesWriterFactory = new NullWriterFactory();
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
            throw new Exception("The uniques writer is not ready!");
        }
        $this->uniqueWriter = $writer;
    }

    function setDuplicatesWriterFactory(WriterFactory $factory){
        $this->duplicatesWriterFactory = $factory;
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
            $this->copyFromUniquesToDuplicates($rowHash);
            $this->removeUnique($rowHash);

            $this->writeDuplicate($rowHash, $row);
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

    private function copyFromUniquesToDuplicates($rowHash){
        $rowIndex = &$this->uniqueRowIndexes[$rowHash];
        if (isset($rowIndex)) {
            $rowPointed = $this->reader->readRow($rowIndex);
            $this->writeDuplicate($rowHash, $rowPointed);
        }
    }

    private function writeDuplicate($hash, $row){
        $this->getDuplicatesWriter($hash)->writeRow($row);
    }

    private function getDuplicatesWriter($rowHash) {
        if (!isset($this->duplicatesWriters[$rowHash])) {
            $this->duplicatesWriters[$rowHash] = $this->duplicatesWriterFactory->createWriter($rowHash);
        }
        return $this->duplicatesWriters[$rowHash];
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

class NullWriter implements Writer{
    function create($path){}
    function isReady(){}
    function writeRow($data){}
}

class NullWriterFactory implements WriterFactory{
    function createWriter($id){
        return new NullWriter();
    }
}