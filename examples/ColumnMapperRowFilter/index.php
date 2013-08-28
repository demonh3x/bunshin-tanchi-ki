<!DOCTYPE html>
<html>
<head>
    <title>ColumnMapperRowFilter example (RowFilter interface)</title>
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
    <h1>ColumnMapperRowFilter example (RowFilter interface)</h1>
    <p>A ColumnMapperRowFilter changes the column names.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashCalculators/ColumnMapperRowFilter.php");

        include_once(__ROOT_DIR__ . "HTML.php");

        /*
         * Create the ColumnMapperRowFilter object.
         * It receives an associative array specifying the column names changes.
         * The old name as the key, the new name as the value.
         */
        $rowFilter = new ColumnMapperRowFilter(array(
            "name" => "FirstName",
            "surname" => "LastName"
        ));


        $input = array(
            "name" => "Jamie",
            "surname" => "MacDow"
        );
        echo "<h2>Input:</h2>";
        echo HTML::table(array($input));

        /*
         * The class ColumnMapperRowFilter implements the RowFilter interface.
         *
         * That interface defines a method:
         * applyTo($row)
         *
         * $row is an associative array with the column names as keys and the values.
         *
         * It will return another associative array with the "transformation" applied.
         */
        $output = $rowFilter->applyTo($input);
        echo "<h2>Output:</h2>";
        echo HTML::table(array($output));
    ?>
</body>
</html>