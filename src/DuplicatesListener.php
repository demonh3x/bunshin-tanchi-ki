<?php

include_once("Row.php");

interface DuplicatesListener {
    function receiveDuplicate(Row $row);
}