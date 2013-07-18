<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>Dedup work at [<?=$_REQUEST["dir"]?>]</h1>

    <h3>Uniques:</h3>
    <?php
        include_once("common.php");

        function showUniquesFile(){
            $uniques_file_match = $_REQUEST["dir"] . "/" . __UNIQUES_FILE__;
            $uniques_files = glob($uniques_file_match);
            $file = $uniques_files[0];

            $uniquesLink = HTML::a($file, $file);

            echo $uniquesLink;
        }
        showUniquesFile();
    ?>


    <h3>Duplicates:</h3>



</body>
</html>