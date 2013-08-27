<?php
namespace Enhance;

include_once("AbstractTestGroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/RowListeners/ExcludingReadersGroupsExportingRowListener.php");
class TestExcludingReadersGroupsExportingRowListener extends AbstractTestGroupsExportingRowListener{

    protected function createListener(\HashCalculator $hashCalculator, \WriterFactory $factory, $excludeRowsFrom = array(), $excludedString = ".excluded"){
        return Core::getCodeCoverageWrapper("ExcludingReadersGroupsExportingRowListener", array($hashCalculator, $factory, $excludeRowsFrom, $excludedString));
    }

    function testNoExcludedReaders(){
        $hCalc = new \StringHashCalculator();
        $mockFactory = new MockRamWriterFactory();
        $listener = $this->createListener($hCalc, $mockFactory, array());

        $data = array(
            array("column" => "value")
        );
        $reader = $this->createRamReader("testNoExcludedReaders", $data);
        $hash = $hCalc->calculate($data[0]);

        Assert::areIdentical(0, count($mockFactory->createdWriters));

        $listener->receiveRow($reader, 0);

        Assert::areIdentical(1, count($mockFactory->createdWriters));

        $writerHashes = array_keys($mockFactory->createdWriters);
        $storedHash = $mockFactory->getRamId($hash);
        Assert::isTrue(in_array($storedHash, $writerHashes));
    }

    function testExcludingReader(){
        $hCalc = new \StringHashCalculator();
        $mockFactory = new MockRamWriterFactory();

        $data = array(
            array("column" => "value")
        );
        $reader = $this->createRamReader("testNoExcludedReaders", $data);
        $excludingString = ".excluded";
        $listener = $this->createListener($hCalc, $mockFactory, array($reader), $excludingString);

        $hash = $hCalc->calculate($data[0]);
        $excludedHash = $hash . $excludingString;

        Assert::areIdentical(0, count($mockFactory->createdWriters));

        $listener->receiveRow($reader, 0);

        Assert::areIdentical(1, count($mockFactory->createdWriters));

        $writerHash = array_keys($mockFactory->createdWriters)[0];
        $storedHash = $mockFactory->getRamId($excludedHash);
        Assert::areIdentical($storedHash, $writerHash);
    }
}