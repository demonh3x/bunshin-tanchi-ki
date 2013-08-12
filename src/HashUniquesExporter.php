<?php

include_once("HashUniquesScanner.php");

include_once("RandomReaders/RandomReader.php");
include_once("Writers/Writer.php");
include_once("Writers/NullWriter.php");

include_once("HashCalculators/RowFilter.php");
include_once("HashCalculators/NullRowFilter.php");

include_once("HashList.php");

class HashUniquesExporter{
    private $scanner;
    private $hashCalculator;
    private $duplicatesListener = null;
    private $readers = array();
    private $uniquesWriter;

    private $uniquesRowFilter;

    function __construct(){
        $this->uniquesWriter = new NullWriter();
        $this->uniquesRowFilter = new NullRowFilter();
    }

    function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    function setUniquesWriter(Writer $uniques, RowFilter $uniquesRowFilter = null){
        $this->uniquesWriter = $uniques;

        if (!is_null($uniquesRowFilter)){
            $this->uniquesRowFilter = $uniquesRowFilter;
        }
    }

    function setDuplicatesWriterFactory(WriterFactory $factory, RowFilter $duplicatesRowFilter = null){
        if (is_null($duplicatesRowFilter)){
            $duplicatesRowFilter = new NullRowFilter();
        }

        $this->duplicatesListener = new DuplicatesExporter(
            $factory,
            $duplicatesRowFilter
        );
    }

    function setHashCalculator(HashCalculator $calculator){
        $this->hashCalculator = $calculator;
    }

    function scan(){
        $this->scanner = new HashUniquesScanner(
            $this->hashCalculator,
            new HashList(),
            $this->readers,
            $this->duplicatesListener
        );

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

    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){
        $filteredRow = $this->rowFilter->applyTo($reader->readRow($rowIndex));
        $writer = $this->getWriter($rowHash);
        $writer->writeRow($filteredRow);
    }

    private function getWriter($hash){
        if (!isset($this->writers[$hash])) {
            $this->writers[$hash] = $this->factory->createWriter($hash);
        }
        return $this->writers[$hash];
    }
}