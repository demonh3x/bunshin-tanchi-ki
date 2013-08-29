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

        include_once(__ROOT_DIR__ . "src/RowListeners/ExportingRowListener.php");

        include_once(__ROOT_DIR__ . "src/Writers/FilteringWriter.php");
        include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");


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


        $firstnameColumn = "Firstname";
        $surnameColumn = "Surname";
        $comparingFilterGroup = new FilterGroup(array(
            //Remove spaces at text's beggining and end.
            new TrimFilter(),
            //Change accented letters to the ones without them.
            new SubstituteAccentsFilter(),
            //Transform the text to lowercase.
            new LowercaseFilter()
        ));

        /*
         * Create the HashUniquesScanner object.
         *
         * It receives in the constructor:
         * - A HashCalculator object to calculate a hash for each row.
         * - A UniquesList object to check if each one of the hashes appeared before.
         * - An array of RandomReaders to get the rows from.
         */
        $scanner = new HashUniquesScanner(
            new StringHashCalculator(
                //Create the Hash from Firstname and Surname columns only.
                array(
                    $firstnameColumn, $surnameColumn
                ),
                //Apply a list of filters to avoid possible "interferences".
                new PerColumnRowFilter(array(
                    $firstnameColumn => $comparingFilterGroup,
                    $surnameColumn => $comparingFilterGroup
                ))
            ),
            //Use a basic UniquesList to check if the hashes are duplicated or not.
            new HashList(),
            //The list of RandomReaders
            array(
                $oldData,
                $newData
            )
        );


        $uniquesGlobalVariable = "uniques";
        $duplicatesGlobalVariable = "duplicates";
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
            //Export the unique results to a Writer with an ExportingRowListener object.
            new ExportingRowListener(
                //Clean the data before outputting it.
                new FilteringWriter(
                    //Write the results to Ram memory (to a global variable).
                    new RamWriter($uniquesGlobalVariable),
                    $cleaningFilterGroup
                )
            ),
            //Export the duplicate results to a Writer with an ExportingRowListener object.
            new ExportingRowListener(
                //Clean the data before outputting it.
                new FilteringWriter(
                    //Write the results to Ram memory (to a global variable).
                    new RamWriter($duplicatesGlobalVariable),
                    $cleaningFilterGroup
                )
            )
        );

        echo "<h2>Uniques data:</h2>";
        printHTMLTable(new RamRandomReader($uniquesGlobalVariable));

        echo "<h2>Duplicates data:</h2>";
        printHTMLTable(new RamRandomReader($duplicatesGlobalVariable));
    ?>
</body>
</html>