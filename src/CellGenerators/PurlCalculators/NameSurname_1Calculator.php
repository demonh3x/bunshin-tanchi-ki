<?php

include_once("BasePurlCalculator.php");

class NameSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . $row[$this->surnameField] . "-1";
    }
}