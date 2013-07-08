<?php

include_once("Comparators/Filters/Filter.php");

class HashCalculator {
    private $columnsToScan = array();
    private $globalFilter, $columnFilters;

    function watchColumns($columns){
        $this->columnsToScan = $columns;
    }

    function calculate($row){
        $hash = "";

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column){
            $value = $row[$column];
            $value = $this->applyGlobalFilterTo($value);
            $value = $this->applyFilterTo($column, $value);
            $hash .= "$column$value";
        }

        return $hash;
    }

    private function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    private function applyGlobalFilterTo($text){
        return $this->isGlobalFilterSet()? $this->globalFilter->applyTo($text) : $text;
    }

    function setGlobalFilter(Filter $filter){
        $this->globalFilter = $filter;
    }

    function setFilter(Filter $filter, $column){
        $this->columnFilters[$column] = $filter;
    }

    private function isGlobalFilterSet(){
        return isset($this->globalFilter);
    }

    private function applyFilterTo($column, $text){
        return $this->isFilterSet($column)? $this->columnFilters[$column]->applyTo($text) : $text;
    }

    private function isFilterSet($column){
        return isset($this->columnFilters[$column]);
    }
}