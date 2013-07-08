<?php

include_once("Filter.php");

class FilterGroup implements Filter{
    private $filters = array();

    function applyTo($text){
        return $this->applyFilters($text);
    }

    function addFilter(Filter $filter){
        $this->filters[] = $filter;
    }

    private function applyFilters($text){
        $filteredText = $text;

        foreach ($this->filters as $filter){
            $filteredText = $filter->applyTo($filteredText);
        }

        return $filteredText;
    }
}