<!DOCTYPE html>
<html>
<head>
    <title>CsvRandomReader example (RandomReader interface)</title>
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
    <h1>CsvRandomReader example (RandomReader interface)</h1>
    <p>A RandomReader reads data rows from somewhere (depends on the implementation, in this case: from a CSV file).</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");

        include_once(__ROOT_DIR__ . "ui/HTML.php");

        /*
         * Create the CsvRandomReader object.
         * It receives the file path in the constructor.
         */
        $reader = new CsvRandomReader("data.csv");


        /*
         * The class CsvRandomReader implements the RandomReader interface.
         *
         * That interface defines a method called:
         * getRowCount()
         *
         * It will return the number of data rows existing in the data source.
         */
        $rowCount = $reader->getRowCount();
        echo "<h2>Row count: <input type='text' disabled='disabled' value='$rowCount'/></h2>";


        /*
         * The class CsvRandomReader implements the RandomReader interface.
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
    <p>Notice that the column names are interpreted as the first data row (because they are actually a data row), and the column names are the column indexes.</p>
</body>
</html>