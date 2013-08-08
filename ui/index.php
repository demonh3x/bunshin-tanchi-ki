<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script>
        $(document).ready(function(){

            var i = $('input[type=file]').size() + 1;

            $('#add').click(function() {
                $('<div><input type="file" class="field" name="file' + i + '"/>' +
                    '</div>').fadeIn('fast').insertBefore('input[name=createNewWork]');
                i++;
            });

            $('#remove').click(function() {
                if(i > 1) {
                    $('.field:last').remove();
                    i--;
                }
            });

        });
    </script>
</head>
<body>
    <h1>Index</h1>

    <?php
        include_once("common.php");
        include_once(__ROOT_DIR__ . "src/HashUniquesExporter.php");
    ?>

    <h2>Create new work:</h2>
    <form name="uploadFiles" enctype="multipart/form-data" action="upload.php" method="POST">
        <div><input type="file" name="file1"/></div>

        <input type="submit" name="createNewWork" value="Submit"/>
        <input type="button" id="add" value="Add file">
        <input type="button" id="remove" value="Remove file">
    </form>

    <h2>View existing works:</h2>
    <a href="<?= __VIEW_DEDUPS_FILE__ ?>">List all works</a>
</body>
</html>