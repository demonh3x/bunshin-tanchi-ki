<?php

include_once("BasePurlCalculator.php");

class Salutation_N_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . substr($row[$this->firstnameField], 0, 1) . "-" . $row[$this->surnameField] . "-1";
    }
}