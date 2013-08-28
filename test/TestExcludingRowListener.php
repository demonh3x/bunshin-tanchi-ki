<?php
namespace Enhance;

use RandomReader;

include_once(__ROOT_DIR__ . "src/RowListeners/ExcludingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

class FakeRowListener implements \RowListener {
    public $data;

    function receiveRow(RandomReader $reader, $rowIndex)
    {
        $this->data[] = $reader->readRow($rowIndex);
    }
}


class TestExcludingRowListener extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    protected function createRamReader($ramId, $readerData){
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);
        foreach ($readerData as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($ramId);
        return $reader;
    }

    private function createExcludingRowListener(\RowListener $listener, $excludeRowsFrom){
        return Core::getCodeCoverageWrapper("ExcludingRowListener", array($listener, $excludeRowsFrom));
    }

    function testReadingRowFromNotExcludedReader(){

        $fakeRowListener = new FakeRowListener();

        $data = array (
            array (
                "name" => "Peter",
                "surname" => "McDonald"
            ),
            array (
                "name" => "Mateu",
                "surname" => "Adsuara"
            )
        );

        $ramReader = $this->createRamReader("reader1", $data);

        $excludeRowsFrom = array(

        );

        $excludingRowListener = $this->createExcludingRowListener($fakeRowListener, $excludeRowsFrom);
        for ($rowIndex = 0; $rowIndex < $ramReader->getRowCount(); $rowIndex++)
        {
            $excludingRowListener->receiveRow($ramReader, $rowIndex);
        }

        Assert::areIdentical($data, $fakeRowListener->data);
    }

    function testReadingRowFromExcludedReader(){

        $fakeRowListener = new FakeRowListener();

        $data = array (
            array (
                "name" => "Peter",
                "surname" => "McDonald"
            ),
            array (
                "name" => "Mateu",
                "surname" => "Adsuara"
            )
        );
        $data2 = array (
            array (
                "name" => "XXXXXXXX",
                "surname" => "XXXXXXXX"
            ),
            array (
                "name" => "111111111",
                "surname" => "111111111"
            )
        );

        $ramReader = $this->createRamReader("reader1", $data);
        $ramReader2 = $this->createRamReader("reader2", $data2);

        $excludeRowsFrom = array(
            $ramReader
        );

        $excludingRowListener = $this->createExcludingRowListener($fakeRowListener, $excludeRowsFrom);
        for ($rowIndex = 0; $rowIndex < $ramReader->getRowCount(); $rowIndex++)
        {
            $excludingRowListener->receiveRow($ramReader, $rowIndex);
        }
        for ($rowIndex = 0; $rowIndex < $ramReader2->getRowCount(); $rowIndex++)
        {
            $excludingRowListener->receiveRow($ramReader2, $rowIndex);
        }

        Assert::areIdentical($data2, $fakeRowListener->data);
    }
}