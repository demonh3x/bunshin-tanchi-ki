<?php

include_once("Filter.php");

class FilterGroup implements Filter{
    private $filters = array();

    function __construct($filters = array()){
        foreach ($filters as $filter){
            $this->addFilter($filter);
        }
    }

    private function addFilter(Filter $filter){
        $this->filters[] = $filter;
    }

    function applyTo($text){
        return $this->applyFilters($text);
    }

    private function applyFilters($text){
        $filteredText = $text;

        foreach ($this->filters as $filter){
            $filteredText = $filter->applyTo($filteredText);
        }

        return $filteredText;
    }
}