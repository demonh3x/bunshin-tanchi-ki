<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");

class HashGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){
        $writer = $this->getWriter($rowHash);
        $writer->writeRow($reader->readRow($rowIndex));
    }
}