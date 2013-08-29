<!DOCTYPE html>
<html>
<head>
    <title>EncryptingWriter example (Writer interface)</title>
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
    <h1>EncryptingWriter example (Writer interface)</h1>
    <p>A Writer writes data rows to somewhere (depends on the implementation).</p>
    <p>EncryptingWriter will encrypt the data before writing it to the other specified Writer.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/Writers/EncryptingWriter.php");

        include_once(__ROOT_DIR__ . "src/Writers/CsvColumnWriter.php");

        include_once(__ROOT_DIR__ . "ui/HTML.php");

        $filePath = "encrypted_output_file.csv";
        if (is_file($filePath)){
            unlink($filePath);
        }

        /*
         * Create the EncryptingWriter object.
         *
         * It receives in the constructor:
         * - the writer to send the encrypted data to.
         * - the password to encrypt the data with.
         */
        $encryptingPassword = "Foo";
        $writer = new EncryptingWriter(
            new CsvColumnWriter($filePath),
            $encryptingPassword
        );


        echo "<h2>Data to write in the file '$filePath':</h2>";
        $data = array(
            array(
                "column1" => "value1A",
                "column2" => "value2A",
            ),
            array(
                "column1" => "value1B",
                "column2" => "value2B",
            ),
            array(
                "column1" => "value1C",
                "column2" => "value2C",
            ),
        );
        echo HTML::table($data);

        /*
         * The class EncryptingWriter implements the Writer interface.
         *
         * That interface defines a method:
         * writeRow($data)
         *
         * It will write the data to the end of the file.
         */
        foreach ($data as $row){
            $writer->writeRow($row);
        }
    ?>
</body>
</html>