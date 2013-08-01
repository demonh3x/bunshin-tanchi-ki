<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
        include_once("common.php");
        include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");
        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");
        include_once(__ROOT_DIR__ . "src/CellGenerators/UniquePURLGenerator.php");
        foreach (glob(__ROOT_DIR__ . "src/HashCalculators/Filters/*.php") as $filename){
            include_once($filename);
        }

        $file = $_REQUEST["file"];

        var_dump($file);
        $deduplicationsWorkFolder = getDeduplicationsWorkFolder($file);
        /*$path = getPath($file);*/

        function getPath($filePath){
            $pathParts = realpath($filePath);
            print_r($pathParts);
        }

        function getOutputFile($inputFile, $index = ""){
            $pathParts = explode("/", $inputFile);
            $inputFileName = $pathParts[count($pathParts) -1];

            $inputFileNameWOExtension = explode(".", $inputFileName, 2)[0];
            $pathParts[count($pathParts)-1] = $inputFileNameWOExtension . ".fg$index.csv";

            $outputFile = implode("/", $pathParts);


            return $outputFile;
        }

        function getNonExistingOutputFile($inputFile){

            $outputFile = getOutputFile($inputFile);
            if (is_file($outputFile)){
                $i = 0;
                $outputFileNumbered = getOutputFile($outputFile, $i);
                while (is_file($outputFileNumbered)){
                    $outputFileNumbered = getOutputFile($outputFile, $i);
                    $i++;
                }
                $outputFile = $outputFileNumbered;
            }

            return $outputFile;
        }

        function getDeduplicationsWorkFolder($file) {
            $pathParts = explode("/", $file);
            $deduplicationsWorkFolder = "";
            $i = 0;
            $duplicatesFolderName = substr(__DUPLICATES_FOLDER__, 0, -1);
            $pattern = "/^" . $duplicatesFolderName . "$/";

            do{
                $deduplicationsWorkFolder .= $pathParts[$i] . "/";
                $i++;
            }while (!preg_match($pattern, $pathParts[$i]));


            return $deduplicationsWorkFolder;
        }

        function getExistingPURLsPath ($deduplicationsWorkFolder) {
            $existingPurlsFile = $deduplicationsWorkFolder . __IDENTIFYING_VALUES_FILE__;
            return $existingPurlsFile;
        }

        function getBeforeGeneratingDuplicatesFolder ($deduplicationsWorkFolder) {
            $beforeGeneratingDuplicatesFolder = $deduplicationsWorkFolder . __DUPLICATES_FOLDER__ . __BEFORE_GENERATING_FOLDER__;
            if (!is_dir($beforeGeneratingDuplicatesFolder)){
                mkdir($beforeGeneratingDuplicatesFolder);
            }
            return $beforeGeneratingDuplicatesFolder;
        }

        function getArrayOfExistingPURLs($file) {
            $reader = new CsvRandomReader();
            $reader->open($file);

            for ($i = 0; $i < $reader->getRowCount(); $i++){
                $arrayUsedPurls[] = $reader->readRow($i)[0];
            }

            return $arrayUsedPurls;
        }
    ?>


    <?php
        $reader = new CsvRandomReader();
        $reader->open($file);

        $outputFile = getNonExistingOutputFile($file);
        $writer = new CsvWriter();
        $writer->create($outputFile);

        $purlColumn = "7";
        $firstNameColumn = "3";
        $lastNameColumn = "4";
        $salutationColumn = "2";

        $existingPurlsFile = getExistingPURLsPath($deduplicationsWorkFolder);
        $arrayUsedPurls = getArrayOfExistingPURLs($existingPurlsFile);

        $uniquePURLGenerator = new UniquePURLGenerator($firstNameColumn, $lastNameColumn, $salutationColumn, $purlColumn, $arrayUsedPurls);

        for ($i = 0; $i < $reader->getRowCount(); $i++){
            $row = $reader->readRow($i);
            $writer->writeRow($uniquePURLGenerator->generate($row));
        }

        $pathParts = explode("/", $file);
        $inputFileName = $pathParts[count($pathParts) -1];

        $beforeGeneratingDuplicatesFolder = getBeforeGeneratingDuplicatesFolder($deduplicationsWorkFolder) . $inputFileName;

        rename($file, $beforeGeneratingDuplicatesFolder);

        //header("Location: " . $_REQUEST["dedupsPageURL"]);
    ?>

    <h1>Generate fields from file: <?= $file ?> to <?= $outputFile ?></h1>
    <hr>
    <h1>Moving: <?= $file ?> to <?=$beforeGeneratingDuplicatesFolder ?></h1>
    <hr>
    <h1>Deduplications work folder:  <?=$deduplicationsWorkFolder ?></h1>
    <hr>
    <h1>Existing PURLs File path:  <?=$existingPurlsFile ?></h1>
</body>
</html>