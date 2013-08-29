<!DOCTYPE html>
<html>
<head>
    <title>DecryptingRandomReader example (RandomReader interface)</title>
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
    <h1>DecryptingRandomReader example (RandomReader interface)</h1>
    <p>A RandomReader reads data rows from somewhere (depends on the implementation).</p>
    <p>The DecryptingRandomReader decrypts the data when reading from another RandomReader.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/RandomReaders/DecryptingRandomReader.php");

        include_once(__ROOT_DIR__ . "src/RandomReaders/CsvColumnRandomReader.php");

        include_once(__ROOT_DIR__ . "ui/HTML.php");

        /*
         * Create the DecryptingRandomReader object.
         *
         * It receives in the constructor:
         * - another RandomReader where to get the data from
         * - the password to decrypt the data with
         */
        $decryptingPassword = "Foo";
        $reader = new DecryptingRandomReader(
            new CsvColumnRandomReader("encrypted_file.csv"),
            $decryptingPassword
        );

        /*
         * The class DecryptingRandomReader implements the RandomReader interface.
         *
         * That interface defines a method called:
         * getRowCount()
         *
         * It will return the number of data rows existing in the data source.
         */
        $rowCount = $reader->getRowCount();
        echo "<h2>Row count: <input type='text' disabled='disabled' value='$rowCount'/></h2>";

        /*
         * The class DecryptingRandomReader implements the RandomReader interface.
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