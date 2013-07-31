<?php

include_once("BasePurlCalculator.php");

class Name_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . "-" . $row[$this->surnameField];
    }
}