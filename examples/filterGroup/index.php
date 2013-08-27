<!DOCTYPE html>
<html>
<head>
    <title>FilterGroup example (Filter interface)</title>
</head>
<body>
    <h1>FilterGroup example (Filter interface)</h1>
    <p>A group filter applies a list of transformations to a text.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FilterGroup.php");

        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/LowercaseFilter.php");
        include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstLetterFilter.php");

        /*
         * Create the FilterGroup object.
         * It receives in the constructor a list of filters to apply.
         */
        $filter = new FilterGroup(array(
            new LowercaseFilter(),
            new FirstLetterFilter()
        ));

        $input = "Jamie MacDow";
        echo "<h2>Input: <input type='text' disabled='disabled' value='$input'/></h2>";

        /*
         * The class FilterGroup implements the Filter interface.
         *
         * That interface defines a method:
         * applyTo($text)
         *
         * And it returns the text with the "transformation" applied.
         *
         * Everywhere a Filter can be used, a FilterGroup object can be used.
         * That's because FilterGroup implements the Filter interface.
         */
        $output = $filter->applyTo($input);
        echo "<h2>Output: <input type='text' disabled='disabled' value='$output'/></h2>";

    ?>
</body>
</html>