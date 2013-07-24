<?php

include_once("Row.php");

interface RowListener {
    function receiveDuplicate(Row $row);
}