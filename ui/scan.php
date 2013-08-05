<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        table td{
            border: 1px solid;
        }
    </style>
</head>
<body>
    <?php
        include_once("common.php");
        set_time_limit(0);

        include_once(__ROOT_DIR__ . "src/HashUniquesExporter.php");
        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");
        foreach (glob(__ROOT_DIR__ . "src/Writers/*.php") as $filename){
            include_once($filename);
        }
        foreach (glob(__ROOT_DIR__ . "src/RandomReaders/*.php") as $filename){
            include_once($filename);
        }

        function rmdir_recursive($dir) {
            $it = new RecursiveDirectoryIterator($dir);
            $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($it as $file) {
                if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
                if ($file->isDir()) rmdir($file->getPathname());
                else unlink($file->getPathname());
            }
            rmdir($dir);
        }

        $INPUT_FILES = getPostVar("inputFiles");

        $UNIQUES_FILE = __DEDUP_DIR__ . getPostVar("dir") .  "/" . "uniques.csv";
        $DUPS_DIR = __DEDUP_DIR__ . getPostVar("dir") . "/" . __DUPLICATES_FOLDER__ ;

        $IDENTIFYING_COLUMN = getPostVar("identifyingColumn");
        $IDENTIFYING_VALUES_FILE = __DEDUP_DIR__ . getPostVar("dir") . "/" . __IDENTIFYING_VALUES_FILE__;

        $CLEANING_COLUMN_FILTERS = getPostVar("cleanFilters");
        $COMPARING_COLUMN_FILTERS = getPostVar("compareFilters");
        $WATCH_COLUMNS = array_keys($COMPARING_COLUMN_FILTERS);

        if (is_dir($DUPS_DIR)){
            rmdir_recursive($DUPS_DIR);
        }
        mkdir($DUPS_DIR);

        if (is_file($UNIQUES_FILE)){
            unlink($UNIQUES_FILE);
        }

        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        echo "<h1>Memory Usage: $memoryUsage MB</h1>";

        $startTime = microtime(true);

        $scanner = new HashUniquesExporter();

        foreach ($INPUT_FILES as $inputFile){
            $reader = new CsvRandomReader();
            $reader->open($inputFile);

            $scanner->addReader($reader);
        }

        $calculator = new StringHashCalculator();
        foreach ($COMPARING_COLUMN_FILTERS as $column => $filters){
            $calculator->setFilter(
                getFilterGroup($filters),
                $column
            );
        }

        $calculator->watchColumns($WATCH_COLUMNS);

        $scanner->setHashCalculator($calculator);

        $uniquesWriter = new CsvWriter();
        $uniquesWriter->create($UNIQUES_FILE);

        $cleaningFilters = new RowFilter();
        foreach ($CLEANING_COLUMN_FILTERS as $column => $filters){
            $cleaningFilters->setFilter(
                getFilterGroup($filters),
                $column
            );
        }

        $scanner->setUniquesWriter($uniquesWriter, $cleaningFilters);

        class CustomWriterFactory implements WriterFactory{
            function createWriter($id){
                global $DUPS_DIR;

                $writer = new CsvWriter();
                $writer->create($DUPS_DIR . "$id.csv");
                if (!$writer->isReady()){
                    throw new Exception("Writer not ready!");
                }
                return $writer;
            }
        }
        $scanner->setDuplicatesWriterFactory(new CustomWriterFactory(), $cleaningFilters);

        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        echo "<h1>Memory Usage: $memoryUsage MB</h1>";

        $scanner->scan();

        echo "<h1>Done!</h1>";


        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $memoryUsage = memory_get_usage(true) / 1024 / 1024;

        echo "<h1>Execution Time: $executionTime seconds</h1>";
        echo "<h1>Memory Usage: $memoryUsage MB</h1>";

        echo "<h1>Creating config file</h1>";
        createConfigFile($IDENTIFYING_COLUMN);

        echo "<h1>Creating identifying data</h1>";
        function createIdentifyingFile(){
            global $IDENTIFYING_VALUES_FILE, $IDENTIFYING_COLUMN;
            global $UNIQUES_FILE;

            if (is_file($IDENTIFYING_VALUES_FILE)){
                unlink($IDENTIFYING_VALUES_FILE);
            }

            $reader = new CsvRandomReader();
            $reader->open($UNIQUES_FILE);

            $writer = new CsvWriter();
            $writer->create($IDENTIFYING_VALUES_FILE);

            for ($rowIndex = 0; $rowIndex < $reader->getRowCount(); $rowIndex++){
                $row = $reader->readRow($rowIndex);
                $identifyingData = array($row[$IDENTIFYING_COLUMN]);
                $writer->writeRow($identifyingData);
            }
        }
        createIdentifyingFile();
        echo "<h1>Finished!</h1>";

        header('Location: ' . getViewDedupLink(getPostVar("dir")));
    ?>
</body>
</html>