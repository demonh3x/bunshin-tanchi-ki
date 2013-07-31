<?php

include_once("BasePurlCalculator.php");

class N_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return substr($row[$this->firstnameField], 0, 1) . "-" . $row[$this->surnameField];
    }
}