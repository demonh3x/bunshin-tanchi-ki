<?php

include_once("Comparator.php");
include_once(__ROOT_DIR__ . "src/Filters/Filter.php");

class StringEqualityComparator implements Comparator{
    private $filters;

    function addFilter(Filter $filter){
    }

    function compare($a, $b){
        return $a === $b;
    }
}