<!DOCTYPE html>
<html>
<head>
    <title>FilteringRandomReader example (RandomReader interface)</title>
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
    <h1>FilteringRandomReader example (RandomReader interface)</h1>
    <p>A RandomReader reads data rows from somewhere (depends on the implementation).</p>
    <p>The FilteringRandomReader applies a RowFilter when reading the data from another RandomReader.<br>
        In this example: ColumnMapperRowFilter to change the column names, because they are not defined in the CSV file.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/RandomReaders/FilteringRandomReader.php");

        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/ColumnMapperRowFilter.php");

        include_once(__ROOT_DIR__ . "HTML.php");

        /*
         * Create the FilteringRandomReader object.
         *
         * It receives in the constructor:
         * - another RandomReader where to get the data from
         * - the RowFilter to apply to the read data
         */
        $reader = new FilteringRandomReader(
            new CsvRandomReader("data.csv"),
            new ColumnMapperRowFilter(array(
                "0" => "ID",
                "1" => "Company",
                "2" => "Salutation",
                "3" => "Firstname",
                "4" => "Surname",
                "5" => "PrintPURL",
                "6" => "Domain_name",
                "7" => "PURL",
            ))
        );

        /*
         * The class FilteringRandomReader implements the RandomReader interface.
         *
         * That interface defines a method called:
         * getRowCount()
         *
         * It will return the number of data rows existing in the data source.
         */
        $rowCount = $reader->getRowCount();
        echo "<h2>Row count: <input type='text' disabled='disabled' value='$rowCount'/></h2>";

        /*
         * The class FilteringRandomReader implements the RandomReader interface.
         *
         * That interface defines another method:
         * readRow($index)
         *
         * It will return the data row pointed by that index.
         */
        $data = array();
        for ($index = 0; $index < $rowCount; $index++){
            $data[] = $reader->readRow($index);
        }
        echo "<h2>Data in the file:</h2>";
        echo HTML::table($data);

    ?>
</body>
</html>