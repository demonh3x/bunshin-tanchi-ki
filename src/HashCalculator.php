<?php

include_once("Comparators/Filters/Filter.php");

class HashCalculator {
    private $columnsToScan = array();
    private $filter;

    function calculate($row){
        $hash = "";

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column){
            $value = $row[$column];
            $value = $this->applyFilterTo($value);
            $hash .= "$column$value";
        }

        return $hash;
    }

    private function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    private function applyFilterTo($text){
        return isset($this->filter)? $this->filter->applyTo($text) : $text;
    }

    function watchColumns($columns){
        $this->columnsToScan = $columns;
    }

    function setFilter(Filter $filter){
        $this->filter = $filter;
    }
}