<?php

include_once("HashCalculator.php");
include_once("RowFilter.php");
include_once("NullRowFilter.php");

class StringHashCalculator implements HashCalculator{
    private $columnsToScan = array();
    private $rowFilter;

    function __construct(Array $watchColumns = array(), RowFilter $filter = null){
        $this->rowFilter = is_null($filter)?
            new NullRowFilter():
            $filter;

        $this->columnsToScan = $watchColumns;
    }

    function calculate(Array $row){
        $hash = "";

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        $filteredRow = $this->rowFilter->applyTo($row);

        foreach ($columns as $column){
            if (isset($row[$column])){
                $value = $filteredRow[$column];
                $hash .= "$column$value";
            }
        }

        return $hash;
    }

    private function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }
}