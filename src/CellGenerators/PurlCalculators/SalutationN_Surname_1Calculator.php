<?php

include_once("BasePurlCalculator.php");

class SalutationN_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . substr($row[$this->firstnameField], 0, 1) . "-" . $row[$this->surnameField] . "-1";
    }
}