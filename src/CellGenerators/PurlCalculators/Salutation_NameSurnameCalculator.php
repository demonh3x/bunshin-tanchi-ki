<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->firstnameField] . $row[$this->surnameField];
    }
}