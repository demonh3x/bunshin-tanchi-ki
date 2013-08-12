<?php

include_once("HashUniquesScanner.php");

include_once("RandomReaders/RandomReader.php");
include_once("Writers/Writer.php");
include_once("Writers/NullWriter.php");

include_once("HashCalculators/RowFilter.php");
include_once("HashCalculators/NullRowFilter.php");

include_once("UniquesList.php");

class HashUniquesExporter{
    private $scanner;
    private $hashCalculator;
    private $duplicatesListener = null;
    private $readers = array();
    private $uniquesWriter;

    private $uniquesRowFilter;

    private $uniquesList;

    function __construct(HashCalculator $hashCalculator, UniquesList $uniquesList, $randomReaders = array()){
        $this->hashCalculator = $hashCalculator;
        $this->uniquesList = $uniquesList;

        foreach ($randomReaders as $reader){
            $this->addReader($reader);
        }

        $this->uniquesWriter = new NullWriter();
        $this->uniquesRowFilter = new NullRowFilter();
    }

    private function addReader(RandomReader $reader){
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

    function scan(){
        $this->scanner = new HashUniquesScanner(
            $this->hashCalculator,
            $this->uniquesList,
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