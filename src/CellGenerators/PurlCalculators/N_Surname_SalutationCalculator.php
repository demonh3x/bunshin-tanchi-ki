<?php

include_once("BasePurlCalculator.php");

class N_Surname_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . "-" . $this->getSurname($row) . $this->getSalutationHyphenatedEnding($row);
    }
}