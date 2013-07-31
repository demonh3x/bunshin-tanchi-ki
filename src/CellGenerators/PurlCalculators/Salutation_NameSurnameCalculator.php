<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-" . $row[$this->firstnameField] . $row[$this->surnameField];
    }
}