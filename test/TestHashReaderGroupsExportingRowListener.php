<?php
namespace Enhance;

include_once("AbstractTestGroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/RowListeners/HashReaderGroupsExportingRowListener.php");
class TestHashReaderGroupsExportingRowListener extends AbstractTestGroupsExportingRowListener{

    protected function createListener(\HashCalculator $hashCalculator, \WriterFactory $factory){
        return Core::getCodeCoverageWrapper("HashReaderGroupsExportingRowListener", array($hashCalculator, $factory));
    }

    function testSameGroupIfSameHashAndReader(){
        $this->assertCreatedGroups(1, 1, 1);
    }

    function testNewGroupIfSameReaderButDifferentHash(){
        $this->assertCreatedGroups(2, 1, 2);
    }

    function testNewGroupIfSameHashButDifferentReader(){
        $this->assertCreatedGroups(2, 2, 1);
    }

    function testNewGroupForEachDifferentReaderAndHashCombination(){
        $this->assertCreatedGroups(4, 2, 2);
    }
}