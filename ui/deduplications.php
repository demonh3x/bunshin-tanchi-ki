<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>Deduplication works:</h1>

    <?php
        include_once("common.php");

        function showDeduplications(){
            $dedups_match = __DEDUP_DIR__ . __DEDUPS_DIRS__;
            $dedups = glob($dedups_match);

            foreach ($dedups as $id => $dedup){
                $link = getViewDedupLink($dedup);
                $dedups[$id] = HTML::a($dedup, $link);
            }

            echo HTML::ul($dedups);
        }
        showDeduplications();
    ?>
</body>
</html>