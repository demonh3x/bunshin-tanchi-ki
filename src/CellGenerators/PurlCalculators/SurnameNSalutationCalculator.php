<?php

include_once("BasePurlCalculator.php");

class SurnameNSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . substr($row[$this->firstnameField], 0, 1) . $row[$this->salutationField];
    }
}