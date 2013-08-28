<?php

include_once("common.php");
include_once(__ROOT_DIR__ . "src/Writers/CsvColumnWriter.php");

$dupsGroupFile = $_REQUEST["file"];
$uniquesFilePath = $_REQUEST["uniquesFilePath"];

$htmlInformation = "";

$dedupDirParts = explode(__DUPLICATES_FOLDER__, $dupsGroupFile);
$dedupDir = substr($dedupDirParts[0], 0, -1);


$reader = new CsvColumnRandomReader($dupsGroupFile);
$writer = new CsvColumnWriter($uniquesFilePath);

for($index = 0; $index < $reader->getRowCount(); $index++)
{
    $htmlInformation .= "<h1>Merging row: $index</h1>";
    $row = $reader->readRow($index);
    $writer->writeRow($row);
}
$reader = null;
$writer = null;

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
    throw new Exception("Could not flag the dups group as merged!");
}


header('Location: ' . getViewDedupLink($dedupDir));




