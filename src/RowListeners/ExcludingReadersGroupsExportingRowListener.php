<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");

class ExcludingReadersGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    private $excludeRowsFrom = array();
    private $excludeString;

    function __construct(WriterFactory $factory, $excludeRowsFrom, $excludeString = ".excluded"){
        parent::__construct($factory);

        $this->excludeString = $excludeString;
        foreach ($excludeRowsFrom as $reader){
            $this->addExclusion($reader);
        }
    }

    private function addExclusion(RandomReader $reader){
        $this->excludeRowsFrom[] = $reader;
    }

    protected function getGroupId(RandomReader $reader, $rowIndex, $rowHash){
        $groupId = $rowHash;
        if (in_array($reader, $this->excludeRowsFrom)){
            $groupId .= $this->excludeString;
        }
        return $groupId;
    }
}