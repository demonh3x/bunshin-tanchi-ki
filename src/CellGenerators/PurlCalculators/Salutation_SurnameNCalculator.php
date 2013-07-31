<?php

include_once("BasePurlCalculator.php");

class Salutation_SurnameNCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-" . $row[$this->surnameField] . substr($row[$this->firstnameField], 0, 1);
    }
}