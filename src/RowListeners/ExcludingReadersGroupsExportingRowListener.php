<?php

include_once("RowListener.php");
include_once("GroupsExportingRowListener.php");

class ExcludingReadersGroupsExportingRowListener extends GroupsExportingRowListener implements RowListener {
    private $excludeRowsFrom = array();
    private $additionalExcludedGroupId;

    function __construct(HashCalculator $hashCalculator, WriterFactory $factory, $excludeRowsFrom, $excludedGroupId = ".excluded"){
        parent::__construct($hashCalculator, $factory);

        $this->additionalExcludedGroupId = $excludedGroupId;
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
            $groupId .= $this->additionalExcludedGroupId;
        }
        return $groupId;
    }
}