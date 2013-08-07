<?php

include_once("BasePurlCalculator.php");

class NameSurnameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . $row[$this->surnameField] . $row[$this->salutationField];
    }
}