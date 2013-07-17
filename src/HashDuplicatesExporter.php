<?php

include_once("RandomReaders/RandomReader.php");
include_once("HashCalculators/HashCalculator.php");
include_once("HashCalculators/NullHashCalculator.php");

include_once("Writers/Writer.php");
include_once("Writers/WriterFactory.php");
include_once("Writers/NullWriterFactory.php");

include_once("HashList.php");
include_once("Row.php");

class HashDuplicatesExporter {
    private $reader, $hashCalculator;
    private $uniqueWriter, $duplicatesWriterFactory;

    private $appearedRows;
    private $duplicatesWriters = array();
    private $uniqueRowIndexes = array();

    function __construct(){
        $this->appearedRows = new HashList();

        $this->uniqueWriter = new NullWriter();
        $this->duplicatesWriterFactory = new NullWriterFactory();
        $this->hashCalculator = new NullHashCalculator();
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
        $this->processAllInputRows();
        $this->writeUniques();
    }

    private function processAllInputRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $this->processRow($rowIndex);
        }
    }

    private function processRow($rowIndex){
        $row = $this->readRow($rowIndex);

        if ($this->isDuplicate($row)) {
            $this->copyFromUniquesToDuplicates($row);
            $this->removeUnique($row);

            $this->writeDuplicate($row);
        } else {
            $this->addUnique($row, $rowIndex);
        }
    }

    private function readRow($rowIndex){
        $row = new Row($this->reader, $rowIndex);
        $row->setHashCalculator($this->hashCalculator);

        return $row;
    }

    private function readRowData($rowIndex){
        return $this->reader->readRow($rowIndex);
    }

    private function isDuplicate(Row $row){
        return $this->appearedRows->contains($row->getHash());
    }

    private function copyFromUniquesToDuplicates(Row $row){
        $rowIndex = &$this->uniqueRowIndexes[$row->getHash()];
        if (isset($rowIndex)) {
            $pointedRow = new Row($this->reader, $rowIndex);
            $pointedRow->setHashCalculator($this->hashCalculator);

            $this->writeDuplicate($pointedRow);
        }
    }

    private function writeDuplicate(Row $row){
        $this->getDuplicatesWriter($row)->writeRow($row->getData());
    }

    private function getDuplicatesWriter(Row $row) {
        if (!isset($this->duplicatesWriters[$row->getHash()])) {
            $this->duplicatesWriters[$row->getHash()] = $this->duplicatesWriterFactory->createWriter($row->getHash());
        }
        return $this->duplicatesWriters[$row->getHash()];
    }

    private function removeUnique(Row $row){
        unset($this->uniqueRowIndexes[$row->getHash()]);
    }

    private function addUnique(Row $row, $rowIndex) {
        $this->appearedRows->add($row->getHash());
        $this->uniqueRowIndexes[$row->getHash()] = $rowIndex;
    }

    private function writeUniques(){
        foreach ($this->uniqueRowIndexes as $rowIndex){
            $row = $this->readRowData($rowIndex);
            $this->uniqueWriter->writeRow($row);
        }
    }
}