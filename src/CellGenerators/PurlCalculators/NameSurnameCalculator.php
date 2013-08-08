<?php

include_once("BasePurlCalculator.php");

class NameSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getSurname($row);
    }
}