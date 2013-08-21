<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/WriterFactory.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class HashGroupExportingRowListener implements RowListener {
    private $writerFactory;
    private $createdWriters = array();

    function __construct(WriterFactory $factory){
        $this->writerFactory = $factory;
    }

    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){
        $writer = &$this->createdWriters[$rowHash];

        if (!isset($writer)){
            $writer = $this->writerFactory->createWriter($rowHash);
        }

        $writer->writeRow($reader->readRow($rowIndex));
    }
}