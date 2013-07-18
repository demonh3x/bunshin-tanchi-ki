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
    <?php
        include_once("common.php");

        function showDeduplications(){
            $dedups_match = $_REQUEST["dir"] . "/" . __DUPLICATES_FOLDER__ . "*";
            $dedups = glob($dedups_match);

            foreach ($dedups as $id => $dedup){
                $link = getViewDupsGroupLink($dedup);
                $dedups[$id] = HTML::a($dedup, $link);
            }

            echo HTML::ul($dedups);
        }
        showDeduplications();
    ?>


</body>
</html>