<?php

include_once("BasePurlCalculator.php");

class NSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . $this->getSurname($row) . "-1";
    }
}