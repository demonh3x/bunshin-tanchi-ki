<?php

include_once("HashUniquesScanner.php");

include_once("Writers/Writer.php");
include_once("Writers/NullWriterFactory.php");

include_once("HashCalculators/RowFilter.php");
include_once("HashCalculators/NullRowFilter.php");

include_once("RowListeners/ExportingRowListener.php");
include_once("Writers/FilteringWriter.php");
include_once("Writers/FilteringWriterFactory.php");
include_once("RowListeners/HashGroupExportingRowListener.php");
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

        $this->scan(
            new ExportingRowListener(
                new FilteringWriter($uniquesWriter, $cleanerFilter)
            ),
            new HashGroupExportingRowListener(
                new FilteringWriterFactory($duplicatesFactory, $cleanerFilter)
            )
        );
    }
}
