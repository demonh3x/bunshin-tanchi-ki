<?php

include_once("BasePurlCalculator.php");

class SurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . $row[$this->firstnameField];
    }
}