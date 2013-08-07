<?php

include_once("BasePurlCalculator.php");

class SalutationName_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . $row[$this->firstnameField] . "-" . $row[$this->surnameField] . "-1";
    }
}