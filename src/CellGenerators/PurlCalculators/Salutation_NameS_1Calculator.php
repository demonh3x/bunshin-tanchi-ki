<?php

include_once("BasePurlCalculator.php");

class Salutation_NameS_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getFirstName($row) . $this->getShortSurname($row) . "-1";
    }
}