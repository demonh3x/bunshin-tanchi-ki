<?php

include_once("BasePurlCalculator.php");

class SurnameName_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getFirstName($row) . $this->getSalutationHyphenatedEnding($row);
    }
}