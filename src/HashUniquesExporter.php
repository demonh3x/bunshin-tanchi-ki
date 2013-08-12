<?php

include_once("HashUniquesScanner.php");

include_once("Writers/Writer.php");
include_once("Writers/NullWriterFactory.php");

include_once("HashCalculators/RowFilter.php");
include_once("HashCalculators/NullRowFilter.php");

class HashUniquesExporter extends HashUniquesScanner{
    function __construct(HashCalculator $hashCalculator, UniquesList $uniquesList, $randomReaders = array()){
        parent::__construct($hashCalculator, $uniquesList, $randomReaders);
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

        $uniqueRows = $this->getUniques($duplicatesListener);
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