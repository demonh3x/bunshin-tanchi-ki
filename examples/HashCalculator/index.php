<!DOCTYPE html>
<html>
<head>
    <title>StringHashCalculator example (HashCalculator interface)</title>
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
    <h1>StringHashCalculator example (HashCalculator interface)</h1>
    <p>A HashCalculator calculates a hash (unique identifier) for a row.</p>
    <p>This hash will be the same for two rows if they are considered duplicates.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/LowercaseFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstLetterFilter.php");

        include_once(__ROOT_DIR__ . "HTML.php");


        $rowFilter = new PerColumnRowFilter(array(
            "name" => new LowercaseFilter(),
            "surname" => new FirstLetterFilter()
        ));

        /*
         * Create the StringHashCalculator object.
         * The constructor can accept two parameters:
         * The first one is the list of columns to calculate the hash from.
         * The second one is a RowFilter to apply when calculating the hash.
         */
        $hashCalculator = new StringHashCalculator(
            array(
                "name"
            ),
            $rowFilter
        );


        $input = array(
            "name" => "Jamie",
            "surname" => "MacDow"
        );
        echo "<h2>Input:</h2>";
        echo HTML::table(array($input));

        /*
         * The class StringHashCalculator implements the HashCalculator interface.
         *
         * That interface defines a method:
         * calculate($row)
         *
         * And it returns the hash.
         */
        $output = $hashCalculator->calculate($input);
        echo "<h2>Output: <input type='text' disabled='disabled' value='$output'/></h2>";
    ?>
</body>
</html>