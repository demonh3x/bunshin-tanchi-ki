<?php

include_once("BasePurlCalculator.php");

class Name_S_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . "-" . substr($row[$this->surnameField], 0, 1) . "-1";
    }
}