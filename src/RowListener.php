<?php

include_once("Row.php");

interface RowListener {
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash);
}