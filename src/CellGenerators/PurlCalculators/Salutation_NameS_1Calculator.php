<?php

include_once("BasePurlCalculator.php");

class Salutation_NameS_1Calculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation . $row[$this->firstnameField] . substr($row[$this->surnameField], 0, 1) . "-1";
    }
}