<?php

class HashCalculator {
    private $columnsToScan = array();

    function calculate($row){
        $hash = "";

        $rowColumns = array_keys($row);
        $columns = $this->areColumnsDefined()? $this->columnsToScan : $rowColumns;

        foreach ($columns as $column){
            $value = $row[$column];
            $hash .= "$column$value";
        }

        return $hash;
    }

    private function areColumnsDefined(){
        return !empty($this->columnsToScan);
    }

    function watchColumns($columns){
        $this->columnsToScan = $columns;
    }
}