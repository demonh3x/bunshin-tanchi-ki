<?php

include_once("RandomReaders/RandomReader.php");

interface RowListener {
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash);
}