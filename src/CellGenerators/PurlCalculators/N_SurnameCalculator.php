<?php

include_once("BasePurlCalculator.php");

class N_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . "-" . $this->getSurname($row);
    }
}