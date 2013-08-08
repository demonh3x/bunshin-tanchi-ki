<?php

include_once("BasePurlCalculator.php");

class SalutationN_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getShortFirstName($row) . "-" . $this->getSurname($row);
    }
}