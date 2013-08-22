<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/WriterFactory.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class HashGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){
        $writer = $this->getWriter($rowHash);
        $writer->writeRow($reader->readRow($rowIndex));
    }
}