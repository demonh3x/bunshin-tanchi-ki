<?php

class UniqueList {
    private $values = array();

    function contains($value){
        return isset($this->values[$value]);
    }

    function add($value){
        $this->values[$value] = "";
    }
}