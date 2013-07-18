<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
        include_once("common.php");
    ?>

    <h1>Dedup work at [<?=$_REQUEST["dir"]?>]</h1>

    <h2>Scan for Dups:</h2>
    <h3>Global Filters:</h3>
    <?= HTML::ul(getAvailableFilters()); ?>

    <h3>Input Files:</h3>
    <?php showInputFiles(); ?>

    <h2>Results:</h2>
    <h3>Uniques:</h3>
    <?php showUniquesFile(); ?>

    <h3>Duplicates:</h3>
    <?php showDupGroups(); ?>
</body>
</html>