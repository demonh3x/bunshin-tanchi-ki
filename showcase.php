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
    include_once("src/Readers/CsvReader.php");
    include_once("src/HashCalculators/StringHashCalculator.php");
    foreach (glob("src/HashCalculators/Filters/*.php") as $filename){
        include_once($filename);
    }

    include_once("HTML.php");

    $scanner = new HashDuplicatesScanner();

    $reader = new CsvReader();
    $reader->open("test/sampleFiles/amaya_data_template.csv");
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

    echo "<h1>Uniques</h1>";
    $uniques = $scanner->getUniques();
    echo HTML::table($uniques);

    echo "<h1>Duplicates</h1>";
    $duplicatesGroups = $scanner->getDuplicates();
    foreach ($duplicatesGroups as $group){
        echo HTML::table($group);
        echo "<br>";
    }
?>
</body>
</html>