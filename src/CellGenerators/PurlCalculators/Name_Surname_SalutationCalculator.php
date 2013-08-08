<?php

include_once("BasePurlCalculator.php");

class Name_Surname_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . "-" . $this->getSurname($row) . $this->getSalutationHyphenatedEnding($row);
    }
}