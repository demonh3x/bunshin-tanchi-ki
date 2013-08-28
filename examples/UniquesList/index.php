<!DOCTYPE html>
<html>
<head>
    <title>HashList filter example (UniquesList interface)</title>
</head>
<body>
    <h1>HashList filter example (UniquesList interface)</h1>
    <p>The UniquesList objects keep track of existing values, and can tell if a value appeared before.</p>
    <?php
        define("__ROOT_DIR__", "../../");

        include_once(__ROOT_DIR__ . "src/HashList.php");

        /*
         * Create the HashList
         */
        $list = new HashList();

        $value = "MacDow";


        /*
         * The class HashList implements the UniquesList interface.
         *
         * That interface defines the method:
         * contains($value)
         *
         * It will return a boolean indicating if the list contains the value.
         */
        $containsValue = $list->contains($value);
        echo "<h2>Before adding '$value'</h2>";

        function printContains(){
            global $containsValue;

            if ($containsValue){
                echo "<p>The list knows it has the value in it.</p>";
            } else {
                echo "<p>The list knows it doesn't have the value in it.</p>";
            }
        }
        printContains();


        /*
         * The class HashList implements the UniquesList interface.
         *
         * That interface defines the method:
         * add($value)
         *
         * It will add the value to the list.
         */
        $list->add($value);
        $containsValue = $list->contains($value);
        echo "<h2>After adding '$value'</h2>";
        printContains();
    ?>
</body>
</html>