<?php

include_once("BasePurlCalculator.php");

class NameSurname_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getSurname($row) . $this->getSalutationHyphenatedEnding($row);
    }
}