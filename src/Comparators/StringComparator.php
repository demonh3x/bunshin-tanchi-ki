<?php

include_once("Comparator.php");
include_once(__ROOT_DIR__ . "src/Comparators/Filters/Filter.php");
include_once(__ROOT_DIR__ . "src/Comparators/Filters/FilterGroup.php");

class StringComparator implements Comparator{
    private $filterGroup;

    function __construct(){
        $this->filterGroup = new FilterGroup();
    }

    function addFilter(Filter $filter){
        $this->filterGroup->addFilter($filter);
    }

    function areEqual($a, $b){
        return $this->filterGroup->filter((string) $a) ===
               $this->filterGroup->filter((string) $b);
    }
}