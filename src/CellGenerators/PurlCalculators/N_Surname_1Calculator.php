<?php

include_once("BasePurlCalculator.php");

class N_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . "-" . $this->getSurname($row) . "-1";
    }
}