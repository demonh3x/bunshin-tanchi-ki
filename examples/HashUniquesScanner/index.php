<!DOCTYPE html>
<html>
<head>
    <title>HashUniquesScanner example</title>
    <style type="text/css">
        table tr:first-child {
            background: lightgrey;
        }
        table td{
            border: 1px solid;
        }
    </style>
</head>
<body>
    <h1>HashUniquesScanner example</h1>
    <p>The HashUniquesScanner is the class that detects the uniques and the duplicates in a series of data sources (RandomReaders).</p>
    <p>In this example we are checking the rows that have the same Firstname and Surname. Applying comparing filters when generating the hashes and cleaning filters to the output Writers.</p>
    <p>We are also excluding the old data from getting into the unique rows output. And separating the excluded old data from the duplicates groups.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashUniquesScanner.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FilterGroup.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/TrimFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/SubstituteAccentsFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/NoSpacesFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/LowercaseFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstNameFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/SurnameFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/UppercaseFirstLetterFilter.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");

        include_once(__ROOT_DIR__ . "src/HashList.php");

        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvColumnRandomReader.php");
        include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

        include_once(__ROOT_DIR__ . "src/RowListeners/ExcludingRowListener.php");
        include_once(__ROOT_DIR__ . "src/RowListeners/ExportingRowListener.php");
        include_once(__ROOT_DIR__ . "src/RowListeners/ExcludingReadersGroupsExportingRowListener.php");

        include_once(__ROOT_DIR__ . "src/Writers/FilteringWriter.php");
        include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

        include_once(__ROOT_DIR__ . "src/Writers/FilteringWriterFactory.php");

        class RamWriterFactory implements WriterFactory{
            const OUTPUT = "output";
            const EXCLUDED = "excluded";

            public $readers = array();

            /**
             * Create a new writer unique for the id.
             * @param $id
             * The identifier to this writer.
             * @return Writer
             * A writer ready to use.
             */
            function createWriter($id){
                $writer = new RamWriter($id);
                $this->storeReaderToShowItLater($id);

                return $writer;
            }

            /**
             * This functionality is not needed, it is just for showing the results directly in the page.
             */
            private function storeReaderToShowItLater($id){
                $excluded = strpos($id, ".excluded");
                $hash = $excluded? substr($id, 0, $excluded) : $id;

                if ($excluded){
                    $this->readers[$hash][self::EXCLUDED] = new RamRandomReader($id);
                } else {
                    $this->readers[$hash][self::OUTPUT] = new RamRandomReader($id);
                }
            }
        }


        include_once(__ROOT_DIR__ . "ui/HTML.php");

        function printHTMLTable(RandomReader $reader){
            $data = array();
            for ($index = 0; $index < $reader->getRowCount(); $index++){
                $data[] = $reader->readRow($index);
            }
            echo HTML::table($data);
        }


        $oldData = new CsvColumnRandomReader("old_data.csv");
        $newData = new CsvColumnRandomReader("new_data.csv");

        echo "<h2>Old data:</h2>";
        printHTMLTable($oldData);

        echo "<h2>New data:</h2>";
        printHTMLTable($newData);


        $comparingFilterGroup = new FilterGroup(array(
            //Remove spaces at text's beggining and end.
            new TrimFilter(),
            //Change accented letters to the ones without them.
            new SubstituteAccentsFilter(),
            //Transform the text to lowercase.
            new LowercaseFilter()
        ));


        $firstnameColumn = "Firstname";
        $surnameColumn = "Surname";

        $hashCalculator = new StringHashCalculator(
            //Create the Hash from Firstname and Surname columns only.
            array(
                $firstnameColumn, $surnameColumn
            ),
            //Apply a list of filters to avoid possible "interferences".
            new PerColumnRowFilter(array(
                $firstnameColumn => $comparingFilterGroup,
                $surnameColumn => $comparingFilterGroup
            ))
        );

        /*
         * Create the HashUniquesScanner object.
         *
         * It receives in the constructor:
         * - A HashCalculator object to calculate a hash for each row.
         * - A UniquesList object to check if each one of the hashes appeared before.
         * - An array of RandomReaders to get the rows from.
         */
        $scanner = new HashUniquesScanner(
            //Use the HashCalculator to generate the hash (unique identifier).
            $hashCalculator,
            //Use a basic UniquesList to check if the hashes are duplicated or not.
            new HashList(),
            //The list of RandomReaders
            array(
                $oldData,
                $newData
            )
        );


        $uniquesGlobalVariable = "uniques";
        $salutationColumn = "Salutation";
        $cleaningFilterGroup = new PerColumnRowFilter(array(
            $salutationColumn => new FilterGroup(array(
                //Remove spaces at text's beggining and end.
                new TrimFilter(),
                //Make the first letter uppercase and the rest lowercase.
                new UppercaseFirstLetterFilter()
            )),
            $firstnameColumn => new FilterGroup(array(
                //Remove spaces at text's beggining and end.
                new TrimFilter(),
                //Special formatting for firstname field.
                new FirstNameFilter()
            )),
            $surnameColumn => new FilterGroup(array(
                //Remove spaces at text's beggining and end.
                new TrimFilter(),
                //Special formatting for surname field.
                new SurnameFilter()
            ))
        ));

        $ramWriterFactory = new RamWriterFactory();

        /*
         * The HashUniquesScanner class defines a method:
         *
         * scan($uniquesRowListener, $duplicatesRowListener)
         *
         * It will scan the RandomReaders for unique and duplicate rows.
         *
         * The method receives 2 parameters:
         * - A RowListener object to receive the unique rows.
         * - A RowListener object to receive the duplicate rows.
         */
        $scanner->scan(
            //Exclude some of the unique output data.
            new ExcludingRowListener(
                //Export the unique results to a Writer with an ExportingRowListener object.
                new ExportingRowListener(
                    //Clean the data before outputting it.
                    new FilteringWriter(
                        //Write the results to Ram memory (to a global variable).
                        new RamWriter($uniquesGlobalVariable),
                        $cleaningFilterGroup
                    )
                ),
                //Exclude the data coming from $oldData RandomReader
                array($oldData)
            ),
            //Export the duplicate results in hash groups and separated by excluded and not excluded data.
            new ExcludingReadersGroupsExportingRowListener(
                $hashCalculator,
                //Clean the data before outputting it.
                new FilteringWriterFactory(
                    //Write the results to Ram memory (to global variables).
                    //This will ask the WriterFactory to create a writer for each different hash.
                    $ramWriterFactory,
                    $cleaningFilterGroup
                ),
                //Exclude the data coming from $oldData RandomReader
                array($oldData)
            )
        );

        echo "<h2>Uniques data:</h2>";
        printHTMLTable(new RamRandomReader($uniquesGlobalVariable));

        foreach ($ramWriterFactory->readers as $hash => $readers){
            echo "<h2>Duplicates group for the hash '$hash':</h2>";
            foreach ($readers as $group => $reader){
                echo "<h3>$group</h3>";
                printHTMLTable($reader);
            }
        }
    ?>
</body>
</html>