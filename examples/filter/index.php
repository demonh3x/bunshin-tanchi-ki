<!DOCTYPE html>
<html>
<head>
    <title>Lowercase filter example (Filter interface)</title>
</head>
<body>
    <h1>Lowercase filter example (Filter interface)</h1>
    <p>The Filters apply a transformation to a text.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/LowercaseFilter.php");

        /*
         * Create the filter object
         */
        $filter = new LowercaseFilter();

        $input = "Jamie MacDow";
        echo "<h2>Input: <input type='text' disabled='disabled' value='$input'/></h2>";

        /*
         * The class LowercaseFilter implements the Filter interface.
         *
         * That interface defines a method:
         * applyTo($text)
         *
         * And it returns the text with the "transformation" applied.
         */
        $output = $filter->applyTo($input);
        echo "<h2>Output: <input type='text' disabled='disabled' value='$output'/></h2>";

    ?>
</body>
</html>