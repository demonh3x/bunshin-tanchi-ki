<?php

include_once("BasePurlCalculator.php");

class SalutationSurnameNCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getSurname($row) . $this->getShortFirstName($row);
    }
}