<?php

include_once("UniquesList.php");

class HashList implements UniquesList{
    private $values = array();

    function contains($value){
        return isset($this->values[$value]);
    }

    function add($value){
        $this->values[$value] = "";
    }
}