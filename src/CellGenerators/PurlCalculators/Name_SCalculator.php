<?php

include_once("BasePurlCalculator.php");

class Name_SCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . "-" . substr($row[$this->surnameField], 0, 1);
    }
}