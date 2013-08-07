<?php

include_once("BasePurlCalculator.php");

class Surname_Name_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . "-" . $row[$this->firstnameField] . "-1";
    }
}