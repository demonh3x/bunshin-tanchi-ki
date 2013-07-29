<?php

include_once("BasePurlCalculator.php");

class NameSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . $row[$this->surnameField];
    }
}