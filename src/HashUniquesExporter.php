<?php

include_once("HashUniquesScanner.php");

include_once("RandomReaders/RandomReader.php");
include_once("Writers/Writer.php");
include_once("Writers/NullWriter.php");

include_once("HashCalculators/RowFilter.php");

include_once("HashList.php");

class HashUniquesExporter{
    private $scanner;
    private $readers = array();
    private $uniquesWriter;

    private $uniquesRowFilter;

    function __construct(){
        $this->uniquesWriter = new NullWriter();
        $this->uniquesRowFilter = new RowFilter();
    }

    function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    function setUniquesWriter(Writer $uniques, RowFilter $uniquesRowFilter = null){
        if (!$uniques->isReady()){
            throw new Exception("The uniques writer is not ready!");
        }
        $this->uniquesWriter = $uniques;

        if (!is_null($uniquesRowFilter)){
            $this->uniquesRowFilter = $uniquesRowFilter;
        }
    }

    function setDuplicatesWriterFactory(WriterFactory $factory, RowFilter $duplicatesRowFilter = null){
        $this->scanner->setDuplicatesListener(
            new DuplicatesExporter(
                $factory,
                is_null($duplicatesRowFilter)? new RowFilter(): $duplicatesRowFilter
            )
        );
    }

    function setHashCalculator(HashCalculator $calculator){
        $this->scanner = new HashUniquesScanner($calculator, new HashList());
        foreach ($this->readers as $reader) {
            $this->scanner->addReader($reader);
        }
    }

    function scan(){
        $uniques = $this->scanner->getUniques();
        foreach ($uniques as $unique){
            $filteredUniqueRow = $this->uniquesRowFilter->applyTo($unique);
            $this->uniquesWriter->writeRow($filteredUniqueRow);
        }
    }
}

include_once("Writers/WriterFactory.php");
class DuplicatesExporter implements RowListener{
    private $factory;
    private $writers = array();

    private $rowFilter;

    function __construct(WriterFactory $factory, RowFilter $rowFilter){
        $this->factory = $factory;
        $this->rowFilter = $rowFilter;
    }

    function receiveRow(Row $row){
        $filteredRow = $this->rowFilter->applyTo($row->getData());
        $writer = $this->getWriter($row->getHash());
        $writer->writeRow($filteredRow);
    }

    private function getWriter($hash){
        if (!isset($this->writers[$hash])) {
            $this->writers[$hash] = $this->factory->createWriter($hash);
        }
        return $this->writers[$hash];
    }
}