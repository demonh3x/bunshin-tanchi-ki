<?php
include_once("common.php");

print_r($_FILES);

$tmp_file = $_FILES["file"]["tmp_name"];

$newDir = getNotExistingDedupDirName();
$dedupDir = explode("/", $newDir);
print_r($dedupDir);
$dedupDir = $dedupDir[count($dedupDir)-2];

mkdir($newDir);
$newDir .= "input/";
mkdir($newDir);
$dest_file = $newDir . $_FILES["file"]["name"];

move_uploaded_file($tmp_file, $dest_file);

header('Location: ' . __VIEW_DEDUP_FILE__ . "?dir=" . __DEDUP_DIR__ . "$dedupDir");