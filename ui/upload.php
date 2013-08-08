<?php
include_once("common.php");

print_r($_FILES);

$newDir = getNotExistingDedupDirName();
$dedupDir = explode("/", $newDir);
print_r($dedupDir);
$dedupDir = $dedupDir[count($dedupDir)-2];
mkdir($newDir);
$newDir .= __INPUTS_FOLDER__;
mkdir($newDir);

$i = 1;

while (isset($_FILES["file" . $i]["tmp_name"]))
{
    $tmp_file = $_FILES["file" . $i]["tmp_name"];

    if (!file_exists($tmp_file)){
        throw new Exception("Could not upload the file!");
    }

    $dest_file = $newDir . $_FILES["file" . $i]["name"];

    move_uploaded_file($tmp_file, $dest_file);
    if (!file_exists($dest_file)){
        throw new Exception("Could not assign the file to the work!");
    }
    $i++;
}

header('Location: ' . getViewDedupLink(__DEDUP_DIR__ . "$dedupDir"));
