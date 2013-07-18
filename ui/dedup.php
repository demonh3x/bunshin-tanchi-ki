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
        table td{
            border: 1px solid;
        }
    </style>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            function addGlobalFilter(filterName){
                var activeGlobalFiltersClass = "activeGlobalFilters";
                $("." + activeGlobalFiltersClass).append($("<li>" + filterName + "</li>"));
            }

            function removeLastGlobalFilter(){
                var activeGlobalFiltersClass = "activeGlobalFilters";
                $("." + activeGlobalFiltersClass + " li").last().remove();
            }

            var globalFilterAdderClass = "globalFiltersAdder";
            var globalFilterAdderButtons = $("." + globalFilterAdderClass + " input[type='button']");
            var globalFilterRemoverClass = "globalFilterRemover";

            globalFilterAdderButtons.on("click", function(){
                addGlobalFilter(this.value);
            });

            $("." + globalFilterRemoverClass).on("click", function(){
                removeLastGlobalFilter();
            });
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

    <h3>Columns to compare:</h3>
    <h4>Input files preview:</h4>
    <?= getInputFilePreviewHTML(getInputFiles(), 3); ?>

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
        <input type="button" value="Try filters"/>
    </form>

    <h3>Apply column comparing filters:</h3>
    <ul class="columnGroup">

    </ul>

    <form action="">
        <input type="submit" value="Scan input files for duplicates"/>
    </form>

    <h2>Results:</h2>
    <h3>Uniques:</h3>
    <?php showUniquesFile(); ?>

    <h3>Duplicates:</h3>
    <?php showDupGroups(); ?>
</body>
</html>