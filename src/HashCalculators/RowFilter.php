<?php

include_once("Filters/Filter.php");

class RowFilter {
    protected $globalFilter, $columnFilters;

    function setGlobalFilter(Filter $filter){
        $this->globalFilter = $filter;
    }

    function setFilter(Filter $filter, $column){
        if (!is_array($column)){
            $column = array($column);
        }

        foreach ($column as $col){
            $this->setFilterTo($col, $filter);
        }
    }

    /**
     * Apply the filtering to a row.
     * @param array $row
     * The input row.
     * @return array
     * The transformed row.
     */
    function applyTo($row){
        $columns = array_keys($row);

        foreach ($columns as $column){
            if (isset($row[$column])){
                $value = &$row[$column];
                $value = $this->applyGlobalFilterTo($value);
                $value = $this->applyFilterTo($column, $value);
            }
        }

        return $row;
    }

    protected function isGlobalFilterSet(){
        return isset($this->globalFilter);
    }

    protected function applyGlobalFilterTo($text){
        return $this->isGlobalFilterSet()? $this->globalFilter->applyTo($text) : $text;
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