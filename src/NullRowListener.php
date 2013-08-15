<?php

include_once ("RowListener.php");

class NullRowListener implements RowListener{
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){}
}