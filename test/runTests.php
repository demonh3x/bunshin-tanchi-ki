<?php
namespace Enhance;
?>

<html>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

    <?php
        define("__ROOT_DIR__", "../");

        define("__TEST_DB_IP__", "localhost");
        define("__TEST_DB_USER__", "root");
        define("__TEST_DB_PASSWORD__", "root");
        define("__TEST_DB_SCHEMA__", "sqlReaderTests");

        include_once('EnhanceTestFramework.php');
        $filterRegex =  "//";
        $excludeRegex = "//";

        foreach (glob("Test*.php") as $filename)
        {
            if ($excludeRegex !== "//" && preg_match($excludeRegex, $filename)){
                continue;
            }
            if (preg_match($filterRegex, $filename)){
                include_once($filename);
            }
        }

        Core::runTests();
    ?>

</html>