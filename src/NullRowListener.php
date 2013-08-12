<?php

class NullRowListener implements RowListener{
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){}
}