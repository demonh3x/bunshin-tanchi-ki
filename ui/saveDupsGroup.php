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

    $identifyingColumn = $_REQUEST["identifyingColumn"];
    echo "<h1>Identifying Column: $identifyingColumn</h1>";

    $dedupDirParts = explode(__DUPLICATES_FOLDER__, $dupsGroupFile);
    $dedupDir = substr($dedupDirParts[0], 0, -1);
    $identifyingValuesFile = $dedupDir . "/" . __IDENTIFYING_VALUES_FILE__;
    echo "<h1>Identifying Values File: $identifyingValuesFile</h1>";

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

    echo "<h1>Updating Identifying Values File: $identifyingValuesFile</h1>";
    $identifyingFileWriter = new CsvWriter();
    $identifyingFileWriter->create($identifyingValuesFile);
    if (!$identifyingFileWriter->isReady()){
        throw new Exception("Could not read the identifying values file! [$identifyingValuesFile]");
    }
    foreach ($arrayFromJavascript as $row){
        $identifyingFileWriter->writeRow(array($row[$identifyingColumn]));
    }


    $fileParts = explode("/", $dupsGroupFile);
    $fileName = $fileParts[count($fileParts)-1];
    $targetDir = $dedupDir . "/" . __DUPLICATES_FOLDER__ . __MERGED_FOLDER__;
    if (!is_dir($targetDir)){
        mkdir($targetDir);
    }
    $target = $targetDir . $fileName;
    echo "<h1>Moving DupsGroup File: $dupsGroupFile to: $target</h1>";
    rename($dupsGroupFile, $target);
    if (!is_file($target)){
        throw new Exception("Could not flag the dups group as merged!");
    }

    header('Location: ' . getViewDedupLink($dedupDir));
