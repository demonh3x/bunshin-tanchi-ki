<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/RowListener.php");
class MockRowListener implements \RowListener{
    public $receivedData = array();

    function receiveRow(\RandomReader $reader, $rowIndex) {
        $this->receivedData[] = $reader->readRow($rowIndex);
    }
}
