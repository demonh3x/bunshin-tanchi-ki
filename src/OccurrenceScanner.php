<?php

include_once("Row.php");
include_once("RowCollection.php");

include_once("HashCalculators/NullRowFilter.php");
include_once("HashCalculators/NullHashCalculator.php");

include_once("RowListeners/RowListener.php");
include_once("RowListeners/NullRowListener.php");

class OccurrenceScanner {
    protected $readers = array(), $regex, $columnsToScan, $rowFilter, $notMatchingListener;
    protected $rowList = array();

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

    function getOccurrences(RowListener $notMatching = null){
        $this->notMatchingListener = is_null($notMatching)? new NullRowListener(): $notMatching;
        $this->processAllReaders();
        return $this->getResultsList();
    }

    private function processAllReaders(){
        foreach ($this->readers as $reader){
            $this->processAllRows($reader);
        }
    }

    protected function processAllRows(RandomReader $reader){
        for ($rowIndex = 0; $rowIndex < $reader->getRowCount(); $rowIndex++) {
            $row = $this->readRow($reader, $rowIndex);
            $this->processRow($row);
        }
    }

    protected function processRow(Row $row){
        $filteredData = $this->rowFilter->applyTo($row->getData());

        $rowColumns = array_keys($filteredData);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column) {
            $value = &$filteredData[$column];
            if (preg_match($this->regex, $value)) {
                $this->addOccurrence($row);
                return;
            }
        }

        $this->sendNotMatching($row);
    }

    protected function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    protected function readRow(RandomReader $reader, $rowIndex){
        return new Row($reader, $rowIndex, new NullHashCalculator());
    }

    protected function addOccurrence(Row $row){
        $this->rowList[] = $row;
    }

    protected function sendNotMatching(Row $row){
        $this->notMatchingListener->receiveRow($row->getReader(), $row->getIndex());
    }

    protected function getResultsList(){
        return new RowCollection($this->rowList);
    }
}
