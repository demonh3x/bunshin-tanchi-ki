<?php

include_once("Filters/Filter.php");
include_once("RowFilter.php");

class PerColumnRowFilter implements RowFilter{
    protected $columnFilters;

    /**
     * Create a RowFilter with different filters per each column.
     * @param array $filters
     * An associative array. The keys are the column names, and the values are the filters to apply to that column.
     */
    function __construct(Array $filters){
        foreach ($filters as $column => $filter){
            $this->setFilterTo($column, $filter);
        }
    }

    function applyTo($row){
        $columns = array_keys($row);

        foreach ($columns as $column){
            if (isset($row[$column])){
                $value = &$row[$column];
                $value = $this->applyFilterTo($column, $value);
            }
        }

        return $row;
    }

    protected function isFilterSet($column){
        return isset($this->columnFilters[$column]);
    }

    protected function setFilterTo($column, Filter $filter){
        $this->columnFilters[$column] = $filter;
    }

    protected function applyFilterTo($column, $text){
        return $this->isFilterSet($column)? $this->columnFilters[$column]->applyTo($text) : $text;
    }
}