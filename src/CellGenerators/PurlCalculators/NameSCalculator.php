<?php

include_once("BasePurlCalculator.php");

class NameSCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . substr($row[$this->surnameField], 0, 1);
    }
}