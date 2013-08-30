<!DOCTYPE html>
<html>
<head>
    <title>UniquesPURLGenerator example (RowFilter interface)</title>
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
    <h1>UniquesPURLGenerator example (RowFilter interface)</h1>
    <p>A UniquesPURLGenerator generates the purl and inserts it in the purl column</p>
    <p>For each call to the applyTo() method, it will generate a different purl.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/CellGenerators/UniquePURLGenerator.php");

        include_once(__ROOT_DIR__ . "ui/HTML.php");


        $salutationColumn = "Salutation";
        $firstnameColumn = "Firstname";
        $surnameColumn = "Surname";
        $purlColumn = "PURL";
        $alreadyUsedPurls = array("JamieM");

        /*
         * Create the UniquePURLGenerator object.
         *
         * It receives in the constructor:
         * - The column name for the first name.
         * - The column name for the surname.
         * - The column name for the salutation.
         * - The column name for the purl.
         * - A list of the purls that have been used before.
         */
        $generator = new UniquePURLGenerator(
            $firstnameColumn,
            $surnameColumn,
            $salutationColumn,
            $purlColumn,
            $alreadyUsedPurls
        );


        $input = array(
            $salutationColumn => "Mr",
            $firstnameColumn => "Jamie",
            $surnameColumn => "MacDow",
            $purlColumn => ""
        );
        echo "<h2>Input:</h2>";
        echo HTML::table(array($input));


        for ($iteration = 1; $iteration <= 1000; $iteration++){
            try {
                /*
                 * The class UniquePURLGenerator implements the RowFilter interface.
                 *
                 * That interface defines a method:
                 * applyTo($row)
                 *
                 * $row is an associative array with the column names as keys and the values.
                 *
                 * It will return another associative array with the "transformation" applied.
                 */
                $output = $generator->applyTo($input);
            }catch (Exception $e){
                printException($e);
                break;
            }

            echo "<h2>Output with the call number $iteration:</h2>";
            echo HTML::table(array($output));
        }

        function printException(Exception $e){
            echo "<p><b>Information: </b>Exception trown '" . get_class($e) . "' with message '";
            echo $e->getMessage() . "' in " . $e->getFile() . ":" . $e->getLine();
            echo " Stack trace: " . $e->getTraceAsString();
            echo " thrown in <b>" . $e->getFile() . "</b> on line <b>" . $e->getLine() . "</b></p>";
        }
    ?>
    <p>When reaching the limit of possible combinations, the applyTo() call will throw an exception.</p>
</body>
</html>