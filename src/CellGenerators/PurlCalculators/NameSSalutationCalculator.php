<?php

include_once("BasePurlCalculator.php");

class NameSSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getShortSurname($row) . $this->getSalutation($row);
    }
}