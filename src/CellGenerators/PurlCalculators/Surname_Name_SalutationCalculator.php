<?php

include_once("BasePurlCalculator.php");

class Surname_Name_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return $row[$this->surnameField] . "-" . $row[$this->firstnameField] . $salutation;
    }
}