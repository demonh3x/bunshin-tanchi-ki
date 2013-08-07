<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->firstnameField] . $row[$this->surnameField] . "-1";
    }
}