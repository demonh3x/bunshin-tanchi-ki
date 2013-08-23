<?php
namespace Enhance;

include_once("AbstractTestGroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/RowListeners/ExcludingReadersGroupsExportingRowListener.php");
class TestExcludingReadersGroupsExportingRowListener extends AbstractTestGroupsExportingRowListener{

    protected function createListener(\WriterFactory $factory, $excludeRowsFrom = array(), $excludedString = ".excluded"){
        return Core::getCodeCoverageWrapper("ExcludingReadersGroupsExportingRowListener", array($factory, $excludeRowsFrom, $excludedString));
    }

    function testNoExcludedReaders(){
        $mockFactory = new MockRamWriterFactory();
        $listener = $this->createListener($mockFactory, array());

        $reader = $this->createRamReader("testNoExcludedReaders", array(
            array("column" => "value")
        ));
        $hash = "hash";

        Assert::areIdentical(0, count($mockFactory->createdWriters));

        $listener->receiveRow($reader, 0, $hash);

        Assert::areIdentical(1, count($mockFactory->createdWriters));

        $writerHashes = array_keys($mockFactory->createdWriters);
        $storedHash = $mockFactory->getRamId($hash);
        Assert::isTrue(in_array($storedHash, $writerHashes));
    }

    function testExcludingReader(){
        $mockFactory = new MockRamWriterFactory();
        $reader = $this->createRamReader("testNoExcludedReaders", array(
            array("column" => "value")
        ));
        $excludingString = ".excluded";
        $listener = $this->createListener($mockFactory, array($reader), $excludingString);

        $hash = "hash";
        $excludedHash = $hash . $excludingString;

        Assert::areIdentical(0, count($mockFactory->createdWriters));

        $listener->receiveRow($reader, 0, $hash);

        Assert::areIdentical(1, count($mockFactory->createdWriters));

        $writerHash = array_keys($mockFactory->createdWriters)[0];
        $storedHash = $mockFactory->getRamId($excludedHash);
        Assert::areIdentical($storedHash, $writerHash);
    }
}