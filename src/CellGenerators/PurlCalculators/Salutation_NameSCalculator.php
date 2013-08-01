<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->firstnameField] . substr($row[$this->surnameField], 0, 1);
    }
}