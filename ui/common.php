<?php
define("__ROOT_DIR__", "../");

define("__DEDUP_DIR__", __ROOT_DIR__ . "deduplications/");
    define("__DEDUPS_DIRS__", "dedup*");
        define("__UNIQUES_FILE__", "uniques.*");
        define("__DUPLICATES_FOLDER__", "duplicates/");
            define("__MERGED_FOLDER__", "merged/");
            define("__BEFORE_GENERATING_FOLDER__", "beforeGenerating/");
        define("__INPUTS_FOLDER__", "input/");
        define("__IDENTIFYING_VALUES_FILE__", "identifyingValues.csv");
        define("__CONFIG_FILE__", "config.xml");

define("__FILTERS_DIR__", __ROOT_DIR__ . "src/HashCalculators/Filters/");

define("__VIEW_UNIQUES_FILE__", "uniques.php");
define("__VIEW_DEDUPS_FILE__", "deduplications.php");
define("__VIEW_DEDUP_FILE__", "dedup.php");
define("__VIEW_DUPS_GROUP_FILE__", "editDupsGroup.php");
define("__GENERATE_FIELDS_FILE__", "generateFields.php");
define("__CUSTOMIZE_FIELDS_GENERATOR__", "customizePURLGenerator.php");

include_once("HTML.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");

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

function getViewDupsGroupLink($file){
    $idColParameter = ((String) getIdentifyingColumn() !== "")?
        "&identifyingColumn=" . getIdentifyingColumn() :
        "";
    return  __VIEW_DUPS_GROUP_FILE__ . "?dupsGroup=" . urlencode($file) . $idColParameter;
}

function getCustomizeFieldsGeneratorLink($file, $dedupsPageURL){
    return __CUSTOMIZE_FIELDS_GENERATOR__ . "?file=" . $file . "&dedupsPageURL=" . $dedupsPageURL;
}

function getUniquesFile(){
    $uniques_file_match = $_REQUEST["dir"] . "/" . __UNIQUES_FILE__;
    $uniques_files = glob($uniques_file_match);

    $file =isset($uniques_files[0])? $uniques_files[0]: "";

    return $file;
}

function getUniquesFileLinkHTML(){
    $file = getUniquesFile();

    $uniquesLink = "";
    if (!empty($file)){
        $reader = new CsvRandomReader();
        $reader->open($file);

        $uniquesLink = HTML::a($file, $file) . " - Rows: " . $reader->getRowCount();
    }

    return $uniquesLink;
}

function getInputFiles(){
    $input_file_match = $_REQUEST["dir"] . "/" . __INPUTS_FOLDER__ . "*";
    $input_files = glob($input_file_match);

    return $input_files;
}

function getInputFilesListHTML(){
    $input_files = getInputFiles();
    foreach ($input_files as $id => $input_file){
        $reader = new CsvRandomReader();
        $reader->open($input_file);

        $input_files[$id] = HTML::a($input_file, $input_file) . " - Rows: " . $reader->getRowCount();
    }

    return HTML::ul($input_files);
}

function getInputFilePreviewHTML($inputFiles, $rowCount){
    if (!is_array($inputFiles)){
        $inputFiles = array($inputFiles);
    }

    $html = "";
    foreach ($inputFiles as $file){
        $reader = new CsvRandomReader();
        $reader->open($file);

        $rows = array();
        for ($i = 0; $i < $rowCount && $reader->getRowCount() > $i; $i++){
            $rows[] = $reader->readRow($i);
        }

        $html .= HTML::table($rows);
    }

    return $html;
}

function getInputFileColumns($inputFile){
    $reader = new CsvRandomReader();
    $reader->open($inputFile);
    if ($reader->getRowCount() > 0){
        $row = $reader->readRow(0);
        return array_keys($row);
    } else {
        return array();
    }
}

function getDupGroups(){
    $dedups_match = $_REQUEST["dir"] . "/" . __DUPLICATES_FOLDER__ . "*";
    $dedups = glob($dedups_match);

    foreach ($dedups as $key => $dedup){
        if (is_dir($dedup)){
            unset($dedups[$key]);
        }
    }

    return $dedups;
}

function getRowCount($file){
    $reader = new CsvRandomReader();
    $reader->open($file);

    return $reader->getRowCount();
}

function getDupGroupsHTML($dedupsPageURL){
    $dedups = getDupGroups();

    foreach ($dedups as $id => $dedup){
        $link = getViewDupsGroupLink($dedup);
        $generateLink = getCustomizeFieldsGeneratorLink($dedup, $dedupsPageURL);
        $dedups[$id] = HTML::a($dedup, $link) . " - [" . HTML::a("Generate PURL", $generateLink) . "]
                       - [" . HTML::a("Download", $dedup) . "] - Rows: " . getRowCount(urldecode($dedup));
    }

    return HTML::ol($dedups);
}

function getAvailableFilters(){
    $filters_match = __FILTERS_DIR__ . "*Filter.php";
    $filters = glob($filters_match);
    $excludedFilters = array("No", "");

    foreach ($filters as $key => $filter){
        $parts = explode("/", $filter);
        $filter = $parts[count($parts)-1];
        $parts = explode("Filter.php", $filter);
        $filter = $parts[0];

        if (in_array($filter, $excludedFilters)){
            unset($filters[$key]);
        } else {
            $filters[$key] = $filter;
        }
    }

    return $filters;
}

function getPostVar($name){
    return json_decode($_REQUEST[$name], true);
}

foreach (glob(__ROOT_DIR__ . "src/HashCalculators/Filters/*.php") as $filename){
    include_once($filename);
}

function getFilterGroup($arrayNames){
    $filters = array();
    foreach ($arrayNames as $name){
        $class = $name . "Filter";
        $filters[] = new $class();
    }
    return FilterGroup::create($filters);
}

function getConfigFile(){
    $dir = getPostVar("dir") != null? getPostVar("dir") : $_REQUEST["dir"];
    $xml = $dir. "/" . __CONFIG_FILE__;
    return $xml;
}

function getFirstXmlValue($xpath){
    $xml = getConfigFile();

    if (is_file($xml)){
        $element = simplexml_load_file($xml);

        $nodes = $element->xpath($xpath);
        $firstNode = count($nodes) > 0? $nodes[0]: array();

        return $firstNode;
    }
    return "";
}

function getIdentifyingColumn(){
    $columnName = getFirstXmlValue("/identifyingColumn/name");
    return $columnName;
}

function createConfigFile($identifyingColumn = ""){
    $data = simplexml_load_string(
        "<identifyingColumn>
            <name>$identifyingColumn</name>
        </identifyingColumn>");

    $data->asXML(getConfigFile());
}