<?php

include_once("HashCalculators/NullRowFilter.php");

include_once("RowListeners/RowListener.php");
include_once("RowListeners/NullRowListener.php");

class OccurrenceScanner {
    protected $readers = array(), $regex, $columnsToScan, $rowFilter;
    protected $matchingListener, $notMatchingListener;

    function __construct($regex, $readers = array(), $columns = array(), RowFilter $rowFilter = null){
        $this->regex = $regex;
        foreach ($readers as $reader){
            $this->addReader($reader);
        }
        $this->columnsToScan = $columns;
        $this->rowFilter = is_null($rowFilter)? new NullRowFilter(): $rowFilter;
    }

    private function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    function scan(RowListener $matching, RowListener $notMatching = null){
        $this->matchingListener = $matching;
        $this->notMatchingListener = is_null($notMatching)? new NullRowListener(): $notMatching;
        $this->processAllReaders();
    }

    private function processAllReaders(){
        foreach ($this->readers as $reader){
            $this->processAllRows($reader);
        }
    }

    protected function processAllRows(RandomReader $reader){
        for ($rowIndex = 0; $rowIndex < $reader->getRowCount(); $rowIndex++) {
            $this->processRow($reader, $rowIndex);
        }
    }

    protected function processRow(RandomReader $reader, $rowIndex){
        $filteredData = $this->rowFilter->applyTo($reader->readRow($rowIndex));

        $rowColumns = array_keys($filteredData);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column) {
            $value = &$filteredData[$column];
            if (preg_match($this->regex, $value)) {
                $this->addOccurrence($reader, $rowIndex);
                return;
            }
        }

        $this->sendNotMatching($reader, $rowIndex);
    }

    protected function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    protected function addOccurrence(RandomReader $reader, $rowIndex){
        $this->matchingListener->receiveRow($reader, $rowIndex);
    }

    protected function sendNotMatching(RandomReader $reader, $rowIndex){
        $this->notMatchingListener->receiveRow($reader, $rowIndex);
    }
}
