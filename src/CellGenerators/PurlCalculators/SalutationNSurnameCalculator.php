<?php

include_once("BasePurlCalculator.php");

class SalutationNSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getShortFirstName($row) . $this->getSurname($row);
    }
}