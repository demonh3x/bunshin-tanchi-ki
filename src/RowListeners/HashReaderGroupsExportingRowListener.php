<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");

class HashReaderGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    protected function getGroupId(RandomReader $reader, $rowIndex, $rowHash){
        return $rowHash . "." . spl_object_hash($reader);
    }
}