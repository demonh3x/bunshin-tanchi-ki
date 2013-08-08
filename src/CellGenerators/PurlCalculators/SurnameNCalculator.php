<?php

include_once("BasePurlCalculator.php");

class SurnameNCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getShortFirstName($row);
    }
}