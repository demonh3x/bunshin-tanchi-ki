<?php

include_once("BasePurlCalculator.php");

class SurnameNameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . $row[$this->firstnameField] . $row[$this->salutationField];
    }
}