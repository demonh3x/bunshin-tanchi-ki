<?php

include_once("BasePurlCalculator.php");

class NameSurnameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getSurname($row) . $this->getSalutation($row);
    }
}