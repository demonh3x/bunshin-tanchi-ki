<?php

include_once("BasePurlCalculator.php");

class SurnameN_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getShortFirstName($row) . "-1";
    }
}