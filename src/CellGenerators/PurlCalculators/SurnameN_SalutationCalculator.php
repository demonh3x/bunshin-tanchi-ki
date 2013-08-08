<?php

include_once("BasePurlCalculator.php");

class SurnameN_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getShortFirstName($row) . $this->getSalutationHyphenatedEnding($row);
    }
}