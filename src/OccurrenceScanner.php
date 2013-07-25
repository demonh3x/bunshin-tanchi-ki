<?php

include_once("Row.php");
include_once("RowCollection.php");

include_once("HashCalculators/RowFilter.php");

class OccurrenceScanner {
    protected $reader, $regex, $columnsToScan, $rowFilter;
    protected $rowList = array();

    function __construct(RandomReader $reader, $regex, $columns = array(), RowFilter $rowFilter = null){
        $this->reader = $reader;
        $this->regex = $regex;
        $this->columnsToScan = $columns;
        $this->rowFilter = is_null($rowFilter)? new RowFilter(): $rowFilter;
    }

    function getOccurrences(){
        $this->processAllRows();
        return $this->getResultsList();
    }

    protected function processAllRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $row = $this->readRow($rowIndex);
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
    }

    protected function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    protected function readRow($rowIndex){
        return new Row($this->reader, $rowIndex);
    }

    protected function addOccurrence(Row $row){
        $this->rowList[] = $row;
    }

    protected function getResultsList(){
        return new RowCollection($this->rowList);
    }
}
