<?php

include_once("BasePurlCalculator.php");

class NSurnameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . $this->getSurname($row) . $this->getSalutation($row);
    }
}