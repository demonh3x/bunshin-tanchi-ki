<?php

include_once("Row.php");

interface RowListener {
    function receiveRow(Row $row);
}