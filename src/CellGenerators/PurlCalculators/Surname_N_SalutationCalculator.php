<?php

include_once("BasePurlCalculator.php");

class Surname_N_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return $row[$this->surnameField] . "-" . substr($row[$this->firstnameField], 0, 1) . $salutation;
    }
}