<?php

include_once("BasePurlCalculator.php");

class Surname_N_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . "-" . $this->getShortFirstName($row) . "-1";
    }
}