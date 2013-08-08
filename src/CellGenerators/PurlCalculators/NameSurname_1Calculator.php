<?php

include_once("BasePurlCalculator.php");

class NameSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getSurname($row) . "-1";
    }
}