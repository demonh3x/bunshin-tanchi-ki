<?php

include_once("BasePurlCalculator.php");

class Surname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . "-" . $row[$this->firstnameField];
    }
}