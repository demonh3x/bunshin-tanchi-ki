<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");

class HashGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    protected function getGroupId(RandomReader $reader, $rowIndex, $rowHash){
        return $rowHash;
    }
}