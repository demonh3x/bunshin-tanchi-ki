<?php

include_once("HashUniquesScanner.php");

include_once("RandomReaders/RandomReader.php");
include_once("Writers/Writer.php");
include_once("Writers/NullWriter.php");

class HashUniquesExporter{
    private $scanner;
    private $reader;
    private $uniquesWriter;

    function __construct(){
        $this->uniquesWriter = new NullWriter();
    }

    function setReader(RandomReader $reader){
        if (!$reader->isReady()){
            throw new Exception("The reader is not ready!");
        }
        $this->reader = $reader;
    }

    function setUniquesWriter(Writer $uniques){
        if (!$uniques->isReady()){
            throw new Exception("The uniques writer is not ready!");
        }
        $this->uniquesWriter = $uniques;
    }

    function setDuplicatesWriterFactory(WriterFactory $factory){
        $this->scanner->setDuplicatesListener(
            new DuplicatesExporter($factory)
        );
    }

    function setHashCalculator(HashCalculator $calculator){
        $this->scanner = new HashUniquesScanner($calculator);
        $this->scanner->addReader($this->reader);
    }

    function scan(){
        $uniques = $this->scanner->getUniques();
        foreach ($uniques as $unique){
            $this->uniquesWriter->writeRow($unique);
        }
    }
}

include_once("Writers/WriterFactory.php");
class DuplicatesExporter implements DuplicatesListener{
    private $factory;
    private $writers = array();

    function __construct(WriterFactory $factory){
        $this->factory = $factory;
    }

    function receiveDuplicate(Row $row){
        $writer = $this->getWriter($row->getHash());
        $writer->writeRow($row->getData());
    }

    private function getWriter($hash){
        if (!isset($this->writers[$hash])) {
            $this->writers[$hash] = $this->factory->createWriter($hash);
        }
        return $this->writers[$hash];
    }
}