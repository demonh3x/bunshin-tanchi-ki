<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style type="text/css">
        h2 {
            color: green;
            text-decoration: underline;
        }
        h3 {
            color: gray;
        }

        table tr:first-child {
            background: lightgrey;
        }
        table td{
            border: 1px solid;
        }
    </style>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            function addLi(ul, value){
                $(ul).append($("<li>" + value + "</li>"));
            }
            function removeLastLi(ul){
                $(ul).find("li").last().remove();
            }

            $(".globalFiltersAdder input[type='button']").on("click", function(){
                addLi($(".activeGlobalFilters"), this.value);
            });

            $(".globalFilterRemover").on("click", function(){
                removeLastLi($(".activeGlobalFilters"));
            });

            $(".columnsToWatch .add").on("click", function(){
                addLi($(".columnsToWatch ul"), $(".columnsToWatch select").find(":selected").text());
            })

            $(".columnsToWatch .remove").on("click", function(){
                removeLastLi($(".columnsToWatch ul"));
            })

            function getUlElements(ul){
                var columns = [];
                $(ul).find("li").each(function(){
                    columns.push($(this).text());
                });
                return columns;
            }

            $(".scanForm input[type=submit]").on("click", function(){
                $(".scanForm input[name=compareColumns]").val(
                    JSON.stringify(getUlElements(".columnsToWatch"))
                );
                $(".scanForm input[name=globalFilters]").val(
                    JSON.stringify(getUlElements(".activeGlobalFilters"))
                );
                $(".scanForm input[name=identifyingColumn]").val(
                    $(".identifyingColumn select").find(":selected").text()
                );
            })
        });
    </script>
</head>
<body>
    <?php
        include_once("common.php");
    ?>

    <h1>Dedup work at [<?=$_REQUEST["dir"]?>]</h1>

    <h2>Configure scanner:</h2>

    <h3>Input files:</h3>
    <?= getInputFilesListHTML() ?>
    <h4>Input files preview:</h4>
    <?= getInputFilePreviewHTML(getInputFiles(), 3); ?>

    <h3>Identifying column (PURL):</h3>
    <div class="identifyingColumn">
        <?= HTML::select(getInputFileColumns(getInputFiles()[0])) ?>
    </div>

    <h3>Columns to compare:</h3>
    <div class="columnsToWatch">
        <ul></ul>
        <?= HTML::select(getInputFileColumns(getInputFiles()[0])) ?>
        <input class="add" type="button" value="Add column"/>
        <input class="remove" type="button" value="Remove last column"/>
    </div>

    <h3>Apply global comparing filters:</h3>
    <form action="" class="globalFiltersAdder">
        <?php
            foreach (getAvailableFilters() as $filter){
                $id = $filter . "GlobalFilter";
                echo "<input id='$id' type='button' name='globalFilters' value='$filter'/>";
            }
        ?>
    </form>
    <ul class="activeGlobalFilters">
        
    </ul>
    <form action="">
        <input class="globalFilterRemover" type="button" value="Remove last filter"/>
        <!--<input type="button" value="Try filters"/>-->
    </form>
<!--
    <h3>Apply column comparing filters:</h3>
    <ul class="columnGroup">

    </ul>-->

    <h3>Scan:</h3>
    <form class="scanForm" method="POST" action="scan.php">
        <input type="hidden" name="dir" value='<?= json_encode($_REQUEST["dir"]) ?>'/>
        <input type="hidden" name="inputFiles" value='<?= json_encode(getInputFiles()) ?>'/>
        <input type="hidden" name="identifyingColumn" value=""/>
        <input type="hidden" name="compareColumns" value=""/>
        <input type="hidden" name="globalFilters" value=""/>
        <input type="submit" value="Scan input files for duplicates"/>
    </form>

    <h2>Results:</h2>
    <h3>Uniques:</h3>
    <?= getUniquesFileLinkHTML(); ?>

    <h3>Duplicate groups:</h3>
    <?= getDupGroupsHTML(); ?>
</body>
</html>