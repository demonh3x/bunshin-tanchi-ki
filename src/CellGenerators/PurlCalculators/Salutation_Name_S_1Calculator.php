<?php

include_once("BasePurlCalculator.php");

class Salutation_Name_S_1Calculator extends BasePurlCalculator{
    function calculate($row){
        $salutation = $row[$this->salutationField];
        if (!empty($salutation)) $salutation .= "-";

        return $salutation  . $row[$this->firstnameField] . "-" . substr($row[$this->surnameField], 0, 1) . "-1";
    }
}