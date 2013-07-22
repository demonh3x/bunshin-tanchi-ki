<?php

include_once("Filters/Filter.php");
include_once("HashCalculator.php");
include_once("RowFilter.php");

class StringHashCalculator implements HashCalculator{
    protected $columnsToScan = array();
    protected $rowFilter;

    function __construct(){
        $this->rowFilter = new RowFilter();
    }

    function watchColumns($columns){
        $this->columnsToScan = $columns;
    }

    function calculate($row){
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

    protected function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    function setGlobalFilter(Filter $filter){
        $this->rowFilter->setGlobalFilter($filter);
    }

    function setFilter(Filter $filter, $column){
        $this->rowFilter->setFilter($filter, $column);
    }
}