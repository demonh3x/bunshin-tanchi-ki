<!DOCTYPE html>
<html>
<head>
    <title>OccurrenceScanner example</title>
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
    <h1>OccurrenceScanner example</h1>
    <p>The OccurrenceScanner is the class that detects the data (from RandomReaders) matching a pattern.</p>
    <p>In this example we are looking for the rows that have 0 or 1 characters (without spaces) in any of the following columns: Salutation, Firstname and Surname.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/OccurrenceScanner.php");

        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvColumnRandomReader.php");
        include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FilterGroup.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/NoSpacesFilter.php");

        include_once(__ROOT_DIR__ . "src/RowListeners/ExportingRowListener.php");

        include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");


        include_once(__ROOT_DIR__ . "ui/HTML.php");

        function printHTMLTable(RandomReader $reader){
            $data = array();
            for ($index = 0; $index < $reader->getRowCount(); $index++){
                $data[] = $reader->readRow($index);
            }
            echo HTML::table($data);
        }


        $reader = new CsvColumnRandomReader("data.csv");

        echo "<h2>Data:</h2>";
        printHTMLTable($reader);


        $salutationColumn = "Salutation";
        $firstnameColumn = "Firstname";
        $surnameColumn = "Surname";
        $preCheckingFilter = new FilterGroup(array(
            //Remove all the spaces in the data
            new NoSpacesFilter()
        ));

        /*
         * Create the OccurrenceScanner object.
         *
         * It receives in the constructor:
         * - A regular expression to check against the data.
         * - An array of RandomReaders to get the rows from.
         * - An array with the names of the columns to look in.
         * - A RowFilter to apply to the data before trying to check the regular expression.
         */
        $scanner = new OccurrenceScanner(
            //The field contains only 0 or 1 characters.
            "/^.{0,1}$/",
            array(
                $reader
            ),
            //Only check in the salutation, firstname and surname columns.
            array(
                $salutationColumn, $firstnameColumn, $surnameColumn
            ),
            //Apply the $preCheckingFilter to salutation, firstname and surname columns.
            new PerColumnRowFilter(array(
                $salutationColumn => $preCheckingFilter,
                $firstnameColumn => $preCheckingFilter,
                $surnameColumn => $preCheckingFilter
            ))
        );



        $matchingGlobalVariable = "matching";
        $notMatchingGlobalVariable = "notMaching";

        /*
         * The OccurrenceScanner class defines a method:
         *
         * scan($matching, $notMatching)
         *
         * It will scan the RandomReaders for data matching the regular expression.
         *
         * The method receives 2 parameters:
         * - A RowListener object to receive the matching rows.
         * - A RowListener object to receive the not matching rows.
         */
        $scanner->scan(
            //Export the matching results to a Writer with an ExportingRowListener object.
            new ExportingRowListener(
                //Write the results to Ram memory (to a global variable).
                new RamWriter($matchingGlobalVariable)
            ),
            //Export the not matching results to a Writer with an ExportingRowListener object.
            new ExportingRowListener(
                //Write the results to Ram memory (to a global variable).
                new RamWriter($notMatchingGlobalVariable)
            )
        );

        echo "<h2>Matching:</h2>";
        printHTMLTable(new RamRandomReader($matchingGlobalVariable));

        echo "<h2>Not matching:</h2>";
        printHTMLTable(new RamRandomReader($notMatchingGlobalVariable));
    ?>
</body>
</html>