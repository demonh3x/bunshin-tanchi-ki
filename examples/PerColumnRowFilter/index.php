<!DOCTYPE html>
<html>
<head>
    <title>PerColumnRowFilter example (RowFilter interface)</title>
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
    <h1>PerColumnRowFilter example (RowFilter interface)</h1>
    <p>A RowFilter applies transformations to the specified columns</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/LowercaseFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstLetterFilter.php");

        include_once(__ROOT_DIR__ . "HTML.php");

        /*
         * Create the PerColumnRowFilter object.
         * It receives an array specifying the filters to apply to each column.
         */
        $rowFilter = new PerColumnRowFilter(array(
            "name" => new LowercaseFilter(),
            "surname" => new FirstLetterFilter()
        ));


        $input = array(
            "name" => "Jamie",
            "surname" => "MacDow"
        );
        echo "<h2>Input:</h2>";
        echo HTML::table(array($input));

        /*
         * The class PerColumnRowFilter implements the RowFilter interface.
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