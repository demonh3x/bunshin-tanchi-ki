<?php

include_once("BasePurlCalculator.php");

class SurnameName_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return $row[$this->surnameField] . $row[$this->firstnameField] . $salutation;
    }
}