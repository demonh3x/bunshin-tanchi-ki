<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/XlsReader.php");

class TestXlsReader extends TestFixture{

    function testOpenFile(){
        $path = __ROOT_DIR__ . 'data/amaya_data_template.xls';
        $reader = new \XlsReader();
        $reader->openFile($path);
        Assert::isTrue($reader->isReady());
    }

}