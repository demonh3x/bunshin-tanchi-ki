<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/HashCalculator.php");
include_once(__ROOT_DIR__ . "src/Writers/WriterFactory.php");

abstract class GroupsExportingRowListener implements RowListener {
    private $hashCalculator;
    private $writerFactory;
    private $createdWriters = array();

    function __construct(HashCalculator $hashCalculator, WriterFactory $factory){
        $this->hashCalculator = $hashCalculator;
        $this->writerFactory = $factory;
    }

    protected function getWriter($id){
        $writer = &$this->createdWriters[$id];

        if (!isset($writer)){
            $writer = $this->writerFactory->createWriter($id);
        }

        return $writer;
    }

    function receiveRow(RandomReader $reader, $rowIndex){
        $rowHash = $this->hashCalculator->calculate($reader->readRow($rowIndex));
        $id = $this->getGroupId($reader, $rowIndex, $rowHash);
        $writer = $this->getWriter($id);
        $writer->writeRow($reader->readRow($rowIndex));
    }

    abstract protected function getGroupId(RandomReader $reader, $rowIndex, $rowHash);
}