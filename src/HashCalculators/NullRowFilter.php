<?php

include_once("RowFilter.php");

class NullRowFilter implements RowFilter{
    function applyTo($row){
        return $row;
    }
}