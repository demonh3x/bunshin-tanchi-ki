<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/WriterFactory.php");

abstract class GroupsExportingRowListener implements RowListener {
    private $writerFactory;
    private $createdWriters = array();

    function __construct(WriterFactory $factory){
        $this->writerFactory = $factory;
    }

    protected function getWriter($id){
        $writer = &$this->createdWriters[$id];

        if (!isset($writer)){
            $writer = $this->writerFactory->createWriter($id);
        }

        return $writer;
    }
}