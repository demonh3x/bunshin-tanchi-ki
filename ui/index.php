<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>Index</h1>

    <?php
        include_once("common.php");
        include_once(__ROOT_DIR__ . "src/HashUniquesExporter.php");
    ?>

    <h2>Create new work:</h2>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
        <input type="file" name="file"/>
        <input type="submit"/>
    </form>

    <h2>View existing works:</h2>
    <a href="<?= __VIEW_DEDUPS_FILE__ ?>">List all works</a>
</body>
</html>