<?php

include_once("BasePurlCalculator.php");

class Salutation_SurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getSurname($row) . $this->getFirstName($row);
    }
}