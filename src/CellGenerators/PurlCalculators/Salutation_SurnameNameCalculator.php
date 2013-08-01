<?php

include_once("BasePurlCalculator.php");

class Salutation_SurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->surnameField] . $row[$this->firstnameField];
    }
}