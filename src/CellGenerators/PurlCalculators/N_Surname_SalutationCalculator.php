<?php

include_once("BasePurlCalculator.php");

class N_Surname_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation = "-" . $salutation;

        return substr($row[$this->firstnameField], 0, 1) . "-" . $row[$this->surnameField] . $salutation;
    }
}