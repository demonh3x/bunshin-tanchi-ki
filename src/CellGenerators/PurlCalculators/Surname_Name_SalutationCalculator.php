<?php

include_once("BasePurlCalculator.php");

class Surname_Name_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . "-" . $this->getFirstName($row) . $this->getSalutationHyphenatedEnding($row);
    }
}