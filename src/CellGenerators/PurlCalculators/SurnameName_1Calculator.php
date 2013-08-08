<?php

include_once("BasePurlCalculator.php");

class SurnameName_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getFirstName($row) . "-1";
    }
}