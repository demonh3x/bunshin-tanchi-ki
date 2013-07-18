<?php
define("__ROOT_DIR__", "../");

define("__DEDUP_DIR__", __ROOT_DIR__ . "deduplications/");
    define("__DEDUPS_DIRS__", "dedup*");
        define("__UNIQUES_FILE__", "uniques.*");
        define("__DUPLICATES_FOLDER__", "duplicates/");
        define("__INPUTS_FOLDER__", "input/");

define("__VIEW_UNIQUES_FILE__", "uniques.php");
define("__VIEW_DEDUPS_FILE__", "deduplications.php");
define("__VIEW_DEDUP_FILE__", "dedup.php");

include_once("HTML.php");

function getNotExistingDedupDirName(){
    $i = 0;
    while (is_dir(__DEDUP_DIR__ . "dedup$i")){
        $i++;
    }
    return __DEDUP_DIR__ . "dedup$i/";
}

function getViewDedupLink($dirToDedup){
    return  __VIEW_DEDUP_FILE__ . "?dir=" . $dirToDedup;
}