<?php

include_once("BasePurlCalculator.php");

class NSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return substr($row[$this->firstnameField], 0, 1) . $row[$this->surnameField] . "-1";
    }
}