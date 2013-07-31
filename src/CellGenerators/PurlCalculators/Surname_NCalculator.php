<?php

include_once("BasePurlCalculator.php");

class Surname_NCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . "-" . substr($row[$this->firstnameField], 0, 1);
    }
}