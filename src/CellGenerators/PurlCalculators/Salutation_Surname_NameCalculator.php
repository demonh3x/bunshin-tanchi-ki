<?php

include_once("BasePurlCalculator.php");

class Salutation_Surname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->surnameField] . "-" . $row[$this->firstnameField];
    }
}