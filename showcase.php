<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        table td{
            border: 1px solid;
        }
    </style>
</head>
<body>
<?php
    include_once("src/HashDuplicatesScanner.php");
    include_once("src/RandomReaders/CsvRandomReader.php");
    include_once("src/HashCalculators/StringHashCalculator.php");
    foreach (glob("src/HashCalculators/Filters/*.php") as $filename){
        include_once($filename);
    }
    foreach (glob("src/Writers/*.php") as $filename){
        include_once($filename);
    }
    foreach (glob("src/RandomReaders/*.php") as $filename){
        include_once($filename);
    }

    include_once("HTML.php");

    $memoryUsage = memory_get_usage(true) / 1024 / 1024;
    echo "<h1>Memory Usage: $memoryUsage MB</h1>";

    $startTime = microtime(true);

    $scanner = new HashDuplicatesScanner();

    $reader = new CsvRandomReader();
    /*$reader->open("test/sampleFiles/15000rows.csv");*/
    $reader->open("test/sampleFiles/45000rows.csv");
    /*$reader->open("test/sampleFiles/100000rows.csv");*/
    $scanner->setReader($reader);

    $calculator = new StringHashCalculator();
    $calculator->setGlobalFilter(FilterGroup::create(
        new TrimFilter(),
        new CutFromFirstSpaceFilter()
    ));
    $calculator->setFilter(FilterGroup::create(
        new OnlyLettersFilter(),
        new UppercaseFirstLetterFilter()
    ), "3");
    $calculator->setFilter(FilterGroup::create(
        new OnlyLettersFilter(),
        new UppercaseFirstLetterFilter()
    ), "4");
    $calculator->watchColumns(array("3", "4"));
    $scanner->setHashCalculator($calculator);

    $uniquesWriter = new CsvWriter();
    $uniquesWriter->create("showcase/uniques.csv");
    $scanner->setUniquesWriter($uniquesWriter);


    class CustomWriterFactory implements WriterFactory{
        function createWriter($id){
            $writer = new CsvWriter();
            $writer->create("showcase/duplicates/$id.csv");
            if (!$writer->isReady()){
                throw new Exception("Writer not ready!");
            }
            return $writer;
        }
    }
    $scanner->setDuplicatesWriterFactory(new CustomWriterFactory());

    $memoryUsage = memory_get_usage(true) / 1024 / 1024;
    echo "<h1>Memory Usage: $memoryUsage MB</h1>";

    $scanner->scan();

    echo "<h1>Done!</h1>";
/*
    function getRamArray($ramId){
        $reader = new RamRandomReader();
        $reader->open($ramId);
        $ret = array();
        for ($i = 0; $i < $reader->getRowCount(); $i++){
            $row = $reader->readRow($i);
            $ret[] = $row;

        }
        return $ret;
    }
    echo "<h1>Uniques</h1>";
    echo HTML::table(getRamArray("ShowcaseUniques"));*/


    $endTime = microtime(true);
    $executionTime = $endTime - $startTime;

    $memoryUsage = memory_get_usage(true) / 1024 / 1024;

    echo "<h1>Execution Time: $executionTime seconds</h1>";
    echo "<h1>Memory Usage: $memoryUsage MB</h1>";

?>
</body>
</html>