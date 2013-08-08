<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <?php

        include_once("common.php");

        $file = $_REQUEST["file"];
        $dedupsPageURL = $_REQUEST["dedupsPageURL"];

        //__GENERATE_FIELDS_FILE__ . "?file=" . $file . "&dedupsPageURL=http://" . $dedupsPageURL;

    ?>

    <h1>Customize Fields Generator</h1>
    <hr>

    <h4>Input files preview:</h4>
    <?= getInputFilePreviewHTML($file, 3); ?>
    <hr>

    <form method="POST"
          action="<?= __GENERATE_FIELDS_FILE__ . "?file=" . $file . "&dedupsPageURL=http://" . $dedupsPageURL ?>">
        <label for="SalutationColumn">Salutation column: </label>
        <?= HTML::select(getInputFileColumns($file), "SalutationColumn") ?>
        <br>
        <label for="FirstNameColumn">First Name column: </label>
        <?= HTML::select(getInputFileColumns($file), "FirstNameColumn") ?>
        <br>
        <label for="LastNameColumn">Last Name column: </label>
        <?= HTML::select(getInputFileColumns($file), "LastNameColumn") ?>
        <br>
        <label for="PurlColumn">Purl Column the column: </label>
        <?= HTML::select(getInputFileColumns($file), "PurlColumn") ?>
        <br>
        <br>
        <input type="submit" value="Generate PURLS"/>
    </form>
</body>
</html>