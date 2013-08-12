<?php

include_once("HashUniquesScanner.php");

include_once("RandomReaders/RandomReader.php");
include_once("Writers/Writer.php");
include_once("Writers/NullWriter.php");
include_once("Writers/NullWriterFactory.php");

include_once("HashCalculators/RowFilter.php");
include_once("HashCalculators/NullRowFilter.php");

include_once("UniquesList.php");

class HashUniquesExporter{
    private $hashCalculator, $uniquesList;
    private $readers = array();

    function __construct(HashCalculator $hashCalculator, UniquesList $uniquesList, $randomReaders = array()){
        $this->hashCalculator = $hashCalculator;
        $this->uniquesList = $uniquesList;

        foreach ($randomReaders as $reader){
            $this->addReader($reader);
        }
    }

    private function addReader(RandomReader $reader){
        $this->readers[] = $reader;
    }

    function export(Writer $uniquesWriter, WriterFactory $duplicatesFactory = null, RowFilter $cleanerFilter = null){
        if (is_null($duplicatesFactory)){
            $duplicatesFactory = new NullWriterFactory();
        }
        if (is_null($cleanerFilter)){
            $cleanerFilter = new NullRowFilter();
        }

        $duplicatesListener = new DuplicatesExporter(
            $duplicatesFactory,
            $cleanerFilter
        );

        $scanner = new HashUniquesScanner(
            $this->hashCalculator,
            $this->uniquesList,
            $this->readers
        );

        $uniqueRows = $scanner->getUniques($duplicatesListener);
        foreach ($uniqueRows as $uniqueRow){
            $uniquesWriter->writeRow(
                $cleanerFilter->applyTo($uniqueRow)
            );
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