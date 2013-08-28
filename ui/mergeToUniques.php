<?php

include_once("common.php");

$dupsGroupFile = $_REQUEST["file"];
$uniquesFilePath = $_REQUEST["uniquesFilePath"];

$htmlInformation = "";

$dedupDirParts = explode(__DUPLICATES_FOLDER__, $dupsGroupFile);
$dedupDir = substr($dedupDirParts[0], 0, -1);


$readDupsGroupFile = new CsvRandomReader($dupsGroupFile);
$dupsGroupData = array();
for($row = 0; $row < $readDupsGroupFile->getRowCount(); $row++)
{
    $htmlInformation .= "<h1>Reading row: $row</h1>";
    $dupsGroupData[] = $readDupsGroupFile->readRow($row);
}

$writer = new CsvWriter($uniquesFilePath);
for($row = 1; $row < count($dupsGroupData); $row++)
{
    $htmlInformation .= "<h1>Writing row: $row</h1>";
    $writer->writeRow($dupsGroupData[$row]);
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
    beforeError();
    throw new Exception("Could not flag the dups group as merged!");
}


header('Location: ' . getViewDedupLink($dedupDir));




