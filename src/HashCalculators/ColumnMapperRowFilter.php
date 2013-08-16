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
            if ($this->isMappingSet($column)){
                $key = $this->getMapping($column);
            } else {
                $key = $column;
            }

            $return[$key] = $value;
        }

        return $return;
    }

    private function getMapping($column){
        return $this->mappings[$column];
    }

    private function isMappingSet($column){
        return isset($this->mappings[$column]);
    }
}