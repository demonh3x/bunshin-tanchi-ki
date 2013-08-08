<?php

include_once("BasePurlCalculator.php");

class SalutationSurname_NCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getSurname($row) . "-" . $this->getShortFirstName($row);
    }
}