<?php

include_once("RowFilter.php");
class ColumnMapperRowFilter implements RowFilter{
    private $mappings;

    function __construct($columnMapping = array()){
        $this->mappings = $columnMapping;
    }

    function applyTo($row){
        $return = array();

        foreach ($row as $column => $value){
            if (isset($this->mappings[$column])){
                $return[$this->mappings[$column]] = $value;
            } else {
                $return[$column] = $value;
            }
        }

        return $return;
    }
}