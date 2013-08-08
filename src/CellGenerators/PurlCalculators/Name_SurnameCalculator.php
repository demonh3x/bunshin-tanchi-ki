<?php

include_once("BasePurlCalculator.php");

class Name_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . "-" . $this->getSurname($row);
    }
}