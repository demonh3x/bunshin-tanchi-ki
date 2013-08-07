<?php

include_once("BasePurlCalculator.php");

class Name_Surname_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return $row[$this->firstnameField] . "-" . $row[$this->surnameField] . $salutation;
    }
}