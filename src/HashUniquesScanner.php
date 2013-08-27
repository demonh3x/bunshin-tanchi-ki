<?php

include_once("HashCalculators/HashCalculator.php");
include_once("RandomReaders/RandomReader.php");

include_once("UniquesList.php");
include_once("Row.php");

include_once("RowListeners/RowListener.php");
include_once("RowListeners/NullRowListener.php");

class HashUniquesScanner {
    private $hashCalculator, $readers = array();

    private $appearedRows;
    private $uniqueRows = array();

    private $uniquesListener, $duplicatesListener;

    function __construct(HashCalculator $calculator, UniquesList $uniquesList, $randomReaders = array()){
        $this->hashCalculator = $calculator;
        $this->appearedRows = $uniquesList;

        foreach ($randomReaders as $randomReader){
            $this->addReader($randomReader);
        }
    }

    function scan(RowListener $uniques, RowListener $duplicates = null){
        $this->uniquesListener = $uniques;

        $this->setDuplicatesListener(
            is_null($duplicates)? new NullRowListener(): $duplicates
        );

        $this->processAllInputRows();
        $this->sendUniques();
    }

    private function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    private function setDuplicatesListener(RowListener $listener){
        $this->duplicatesListener = $listener;
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
        $row = new Row($reader, $rowIndex, $this->hashCalculator);
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

    private function sendUniques() {
        foreach ($this->uniqueRows as $row) {
            $this->uniquesListener->receiveRow($row->getReader(), $row->getIndex());
        }
    }

    private function sendDuplicate(Row $row){
        $this->duplicatesListener->receiveRow($row->getReader(), $row->getIndex());
    }
}