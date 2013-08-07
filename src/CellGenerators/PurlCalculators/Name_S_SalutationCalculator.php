<?php

include_once("BasePurlCalculator.php");

class Name_S_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return $row[$this->firstnameField] . "-" . substr($row[$this->surnameField], 0, 1) . $salutation;
    }
}