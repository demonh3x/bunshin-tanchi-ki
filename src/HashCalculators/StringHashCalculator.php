<?php

include_once("Filters/Filter.php");
include_once("HashCalculator.php");

class StringHashCalculator implements HashCalculator{
    protected $columnsToScan = array();
    protected $globalFilter, $columnFilters;

    function watchColumns($columns){
        $this->columnsToScan = $columns;
    }

    function calculate($row){
        $hash = "";

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column){
            if (isset($row[$column])){
                $value = $row[$column];
                $value = $this->applyGlobalFilterTo($value);
                $value = $this->applyFilterTo($column, $value);
                $hash .= "$column$value";
            }
        }

        return $hash;
    }

    protected function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    protected function applyGlobalFilterTo($text){
        return $this->isGlobalFilterSet()? $this->globalFilter->applyTo($text) : $text;
    }

    function setGlobalFilter(Filter $filter){
        $this->globalFilter = $filter;
    }

    function setFilter(Filter $filter, $column){
        if (is_array($column)){
            foreach ($column as $col){
                $this->setFilterToColumn($filter, $col);
            }
        } else {
            $this->setFilterToColumn($filter, $column);
        }
    }

    protected function setFilterToColumn(Filter $filter, $column){
        $this->columnFilters[$column] = $filter;
    }

    protected function isGlobalFilterSet(){
        return isset($this->globalFilter);
    }

    protected function applyFilterTo($column, $text){
        return $this->isFilterSet($column)? $this->columnFilters[$column]->applyTo($text) : $text;
    }

    protected function isFilterSet($column){
        return isset($this->columnFilters[$column]);
    }
}