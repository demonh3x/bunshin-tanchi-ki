<?php

include_once('UniquesList.php');
include_once('HashList.php');
include_once('HashCalculators/Filters/Filter.php');

class FilteredHashList extends HashList implements UniquesList {
    private $checkingFilter;

    function __construct(Filter $checkingFilter = null){
        $this->checkingFilter = is_null($checkingFilter)? new NoFilter(): $checkingFilter;
    }

    function add($value){
        return parent::add($this->checkingFilter->applyTo($value));
    }

    function contains($value){
        return parent::contains($this->checkingFilter->applyTo($value));
    }
}