<?php

namespace Enhance;

include_once("AbstractTestGroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/RowListeners/HashGroupsExportingRowListener.php");
class TestHashGroupsExportingRowListener extends AbstractTestGroupsExportingRowListener{

    protected function createListener(\WriterFactory $factory){
        return Core::getCodeCoverageWrapper("HashGroupsExportingRowListener", array($factory));
    }

    function testSameGroupIfSameHashAndReader(){
        $this->assertCreatedGroups(1, 1, 1);
    }

    function testNewGroupIfSameReaderButDifferentHash(){
        $this->assertCreatedGroups(2, 1, 2);
        $this->assertCreatedGroups(2, 2, 2);
    }

    function testSameGroupIfSameHashButDifferentReader(){
        $this->assertCreatedGroups(1, 2, 1);
    }
}