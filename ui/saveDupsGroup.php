<?php
    include_once("../src/Writers/CsvWriter.php");
    include_once("common.php");

    $htmlInformation = "";
    $uniquesFilePath = $_REQUEST["uniquesFilePath"];
    $htmlInformation .= "<h1>Uniques File Path: $uniquesFilePath</h1>";

    $dupsGroupFile = $_REQUEST["dupsGroupFilePath"];
    $htmlInformation .= "<h1>DupsGroup File Path: $dupsGroupFile</h1>";

    $stringFromJavascript = $_POST['arrayAsString'];
    $arrayFromJavascript = json_decode($stringFromJavascript, TRUE);
    $htmlInformation .= "<h1>Merging data:</h1>";
    $htmlInformation .= print_r($arrayFromJavascript, true);

    $identifyingColumn = $_REQUEST["identifyingColumn"];
    $htmlInformation .= "<h1>Identifying Column: $identifyingColumn</h1>";

    $dedupDirParts = explode(__DUPLICATES_FOLDER__, $dupsGroupFile);
    $dedupDir = substr($dedupDirParts[0], 0, -1);
    $identifyingValuesFile = $dedupDir . "/" . __IDENTIFYING_VALUES_FILE__;
    $htmlInformation .= "<h1>Identifying Values File: $identifyingValuesFile</h1>";

    $writer = new CsvWriter();

    $writer->create($uniquesFilePath);
    if (!$writer->isReady()){
        echo $htmlInformation;
        throw new Exception("Could not open the uniques file to write! [$uniquesFilePath]");
    }

    $uniquesReader = new CsvRandomReader();
    $uniquesReader->open($uniquesFilePath);
    if (!$uniquesReader->isReady()){
        echo $htmlInformation;
        throw new Exception("Could not read the uniques file! [$uniquesFilePath]");
    }
    $initialRows = $uniquesReader->getRowCount();

    foreach ($arrayFromJavascript as $key=>$row){
        $htmlInformation .= "<h1>Writing row: $key</h1>";
        $htmlInformation .= print_r($row, true);
        $writer->writeRow($row);
    }

    $uniquesReaderAfterWriting = new CsvRandomReader();
    $uniquesReaderAfterWriting->open($uniquesFilePath);
    if (!$uniquesReaderAfterWriting->isReady()){
        echo $htmlInformation;
        throw new Exception("Could not read the uniques file to check the merge! [$uniquesFilePath]");
    }
    $finalRows = $uniquesReaderAfterWriting->getRowCount();

    if (($finalRows - $initialRows) !== count($arrayFromJavascript)){
        echo $htmlInformation;
        throw new Exception("Could not write all the rows to the uniques file!");
    }

    $htmlInformation .= "<h1>Updating Identifying Values File: $identifyingValuesFile</h1>";
    $identifyingFileWriter = new CsvWriter();
    $identifyingFileWriter->create($identifyingValuesFile);
    if (!$identifyingFileWriter->isReady()){
        echo $htmlInformation;
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
    $htmlInformation .= "<h1>Moving DupsGroup File: $dupsGroupFile to: $target</h1>";
    rename($dupsGroupFile, $target);
    if (!is_file($target)){
        echo $htmlInformation;
        throw new Exception("Could not flag the dups group as merged!");
    }

    header('Location: ' . getViewDedupLink($dedupDir));
    echo $htmlInformation;
