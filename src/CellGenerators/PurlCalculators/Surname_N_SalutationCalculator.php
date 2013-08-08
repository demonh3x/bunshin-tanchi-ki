<?php

include_once("BasePurlCalculator.php");

class Surname_N_SalutationCalculator extends BasePurlCalculator{
    function calculate($row){



        return $this->getSurname($row) . "-" . $this->getShortFirstName($row) . $this->getSalutationHyphenatedEnding($row);
    }
}