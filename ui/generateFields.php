<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
        include_once("common.php");
        include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");
        foreach (glob(__ROOT_DIR__ . "src/HashCalculators/Filters/*.php") as $filename){
            include_once($filename);
        }

        $file = $_REQUEST["file"];
        /*$path = getPath($file);*/

        function getPath($filePath){
            $pathParts = realpath($filePath);
            print_r($pathParts);
        }

        function getOutputFile($inputFile, $index = ""){
            $pathParts = explode("/", $inputFile);
            $inputFileName = $pathParts[count($pathParts) -1];

            $inputFileNameWOExtension = explode(".", $inputFileName, 2)[0];
            $outputFile = $inputFileNameWOExtension . ".fg$index.csv";

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
        for ($i = 0; $i < $reader->getRowCount(); $i++){
            $row = $reader->readRow($i);

            $filters = FilterGroup::create(
                new TrimFilter(),
                new CutFromFirstSpaceFilter(),
                new UppercaseFirstLetterFilter()
            );
            $firsName = $filters->applyTo($row[$firstNameColumn]);
            $lastName = $filters->applyTo($row[$lastNameColumn]);

            $row[$purlColumn] = $firsName . $lastName;

            $writer->writeRow($row);
        }
    ?>

    <h1>Generate fields from file: <?= $file ?> to <?= $outputFile ?></h1>
</body>
</html>