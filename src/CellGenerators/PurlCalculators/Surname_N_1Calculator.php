<?php

include_once("BasePurlCalculator.php");

class Surname_N_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->surnameField] . "-" . substr($row[$this->firstnameField], 0, 1) . "-1";
    }
}