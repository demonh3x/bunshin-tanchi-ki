<?php
    include_once("../src/Writers/CsvWriter.php");
    include_once("common.php");


    $uniquesFilePath = $_REQUEST["uniquesFilePath"];
    echo "<h1>Uniques File Path: $uniquesFilePath</h1>";

    $dupsGroupFile = $_REQUEST["dupsGroupFilePath"];
    echo "<h1>DupsGroup File Path: $dupsGroupFile</h1>";

    $stringFromJavascript = $_POST['arrayAsString'];
    $arrayFromJavascript = json_decode($stringFromJavascript, TRUE);
    echo "<h1>Merging data:</h1>";
    print_r($arrayFromJavascript);

    echo "<h1>" . $_REQUEST["identifyingColumn"] . "</h1>";
/*
    $writer = new CsvWriter();

    $writer->create($uniquesFilePath);
    if (!$writer->isReady()){
        throw new Exception("Could not open the uniques file to write! [$uniquesFilePath]");
    }

    $uniquesReader = new CsvRandomReader();
    $uniquesReader->open($uniquesFilePath);
    if (!$uniquesReader->isReady()){
        throw new Exception("Could not read the uniques file! [$uniquesFilePath]");
    }
    $initialRows = $uniquesReader->getRowCount();

    foreach ($arrayFromJavascript as $key=>$row){
        echo "<h2>Writing row: $key</h2>";
        print_r($row);
        $writer->writeRow($row);
    }

    $uniquesReaderAfterWriting = new CsvRandomReader();
    $uniquesReaderAfterWriting->open($uniquesFilePath);
    if (!$uniquesReaderAfterWriting->isReady()){
        throw new Exception("Could not read the uniques file to check the merge! [$uniquesFilePath]");
    }
    $finalRows = $uniquesReaderAfterWriting->getRowCount();

    if (($finalRows - $initialRows) !== count($arrayFromJavascript)){
        throw new Exception("Could not write all the rows to the uniques file!");
    }

    echo "<h1>Deleting DupsGroup File: $dupsGroupFile</h1>";
    unlink($dupsGroupFile);


    $dedupDirParts = explode(__DUPLICATES_FOLDER__, $dupsGroupFile);
    $dedupDir = substr($dedupDirParts[0], 0, -1);
    header('Location: ' . getViewDedupLink($dedupDir));*/
