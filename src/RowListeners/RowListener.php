<?php

include_once(__ROOT_DIR__ . "src/RandomReaders/RandomReader.php");

interface RowListener {
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash);
}