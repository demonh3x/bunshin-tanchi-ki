<?php

include_once("Filters/Filter.php");
include_once("HashCalculator.php");
include_once("StringHashCalculator.php");

class RegexHashCalculator extends StringHashCalculator implements HashCalculator{
    protected $regex;

    function setRegex($regex){
        $this->regex = $regex;
    }

    function calculate($row){
        $hash = microtime();

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        $filteredRow = $this->rowFilter->applyTo($row);

        foreach ($columns as $column){
            if (isset($row[$column])){
                $value = $filteredRow[$column];

                if (preg_match($this->regex, $value)){
                    $hash = "Found";
                    break;
                }
            }
        }

        return $hash;
    }
}