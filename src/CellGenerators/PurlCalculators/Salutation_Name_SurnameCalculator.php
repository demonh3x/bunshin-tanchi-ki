<?php

include_once("BasePurlCalculator.php");

class Salutation_Name_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-"  . $row[$this->firstnameField] . "-" . $row[$this->surnameField];
    }
}