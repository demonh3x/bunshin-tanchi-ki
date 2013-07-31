<?php
include_once("common.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

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

        em {
            text-decoration: underline;
        }

        .comparing-section {
            background-color: #ffebb7;
        }

        .comparing-section ul,
        .comparing-section ol{
            background-color: #bca985;
            border: 1px solid #e9d6a1;
        }

        .cleaning-section {
            background-color: #beffc1;
        }

        .cleaning-section ul,
        .cleaning-section ol{
            background-color: #8cc38d;
            border: 1px solid #a9e9ac;
        }
    </style>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            function addLi(ul, value, datacolumn){
                datacolumn = typeof datacolumn !== 'undefined' ?
                   " data-column='" + datacolumn + "'" :
                   "";

                $(ul).append($("<li" + datacolumn + ">" + value + "</li>"));
            }
            function removeLastLi(ul){
                $(ul).find("> li").last().remove();
            }

            function getColumnHTML(){
                var html =  "<div class='filters-container'>";
                    html +=     "<ul class='filter-list'>";
                    html +=     "</ul>";
                    <?php foreach (getAvailableFilters() as $filter){
                        echo "html += \"<input class='filter-adder' type='button' value='$filter'/>\";";
                    }?>
                    html += "</div>";
                    html += "<input class='filter-remover' type='button' value='Remove last filter'/>";
                    /*html += "<input class='filter-tryer' type='button' value='Try filters'/>";*/

                return html;
            }

            $(".column-adder").on("click", function(){
                var columnName = $(this).parent().find(".column-selector option:selected").text();
                var ul = $(this).parent().find(".columns");

                var html = "<h4>Column " + columnName + "</h4>" + getColumnHTML();
                addLi(ul, html, columnName);
                setFilterEvents();
            });

            $(".column-remover").on("click", function(){
                var ul = $(this).parent().find(".columns");
                removeLastLi(ul);
            });

            function setFilterEvents(){
                $(".filter-adder").unbind("click").on("click", function(){
                    var ul = $(this).parent().find(".filter-list");
                    var value = this.value;
                    addLi(ul, value);
                });

                $(".filter-remover").unbind("click").on("click", function(){
                    var ul = $(this).parent().find(".filter-list");
                    removeLastLi(ul);
                });
            }
            setFilterEvents();


            function getUlElements(ul){
                var columns = [];
                $(ul).find("li").each(function(){
                    columns.push($(this).text());
                });
                return columns;
            }

            function getColumnsFilters(container){
                var columns = {};
                $(container).find(".columns li").each(function(){
                    var columnName = $(this).attr("data-column");
                    var ul = $(this).find(".filter-list").first();
                    columns[columnName] = getUlElements(ul);
                });

                delete columns[undefined];
                return columns;
            }

            $(".scanForm input[type=submit]").on("click", function(){
                $(".scanForm input[name=identifyingColumn]").val(
                    $(".identifyingColumn select").find(":selected").text()
                );
                $(".scanForm input[name=compareFilters]").val(
                    JSON.stringify(getColumnsFilters(".columns-to-compare"))
                );
                $(".scanForm input[name=cleanFilters]").val(
                    JSON.stringify(getColumnsFilters(".columns-to-clean"))
                );
            })
        });
    </script>
</head>
<body>
    <h1>Dedup work at [<a href="<?=$_REQUEST["dir"]?>"><?=$_REQUEST["dir"]?></a>]</h1>

    <hr>
    <h2>Results:</h2>
    <h3>Uniques:</h3>
    <?= getUniquesFileLinkHTML(); ?>

    <h3>Duplicate groups:</h3>
    <?= getDupGroupsHTML(); ?>

    <hr>
    <h2>Configure scanner:</h2>

    <h3>Input files:</h3>
    <?= getInputFilesListHTML() ?>
    <h4>Input files preview:</h4>
    <?= getInputFilePreviewHTML(getInputFiles(), 3); ?>

    <hr>
    <h3>Identifying column (PURL):</h3>
    <div class="identifyingColumn">
        <?= HTML::select(getInputFileColumns(getInputFiles()[0])) ?>
    </div>

    <div class="comparing-section">
        <h3>Apply column <em>comparing</em> rules:</h3>
        <p>Changes made by the filters <em>will not be saved</em> in the output files.</p>
        <p>The scanner will use this columns to determine the uniqueness of each row.
            If no column is selected, it'll use all the columns.</p>
        <div class="columns-to-compare">
            <ul class="columns">
            </ul>
            <?= HTML::select(getInputFileColumns(getInputFiles()[0]), "column-selector") ?>
            <input class="column-adder" type="button" value="Add column"/>
            <input class="column-remover" type="button" value="Remove last column"/>
        </div>
    </div>

    <div class="cleaning-section">
        <h3>Apply column <em>cleaning</em> rules:</h3>
        <p>Changes made by the filters <em>will be saved</em> in the output files.</p>
        <p>The scanner will use this filters to clean the data.</p>
        <div class="columns-to-clean">
            <ul class="columns">
            </ul>
            <?= HTML::select(getInputFileColumns(getInputFiles()[0]), "column-selector") ?>
            <input class="column-adder" type="button" value="Add column"/>
            <input class="column-remover" type="button" value="Remove last column"/>
        </div>
    </div>

    <h3>Scan:</h3>
    <form class="scanForm" method="POST" action="scan.php">
        <input type="hidden" name="dir" value='<?= json_encode($_REQUEST["dir"]) ?>'/>
        <input type="hidden" name="inputFiles" value='<?= json_encode(getInputFiles()) ?>'/>
        <input type="hidden" name="identifyingColumn" value=""/>
        <input type="hidden" name="compareFilters" value=""/>
        <input type="hidden" name="cleanFilters" value=""/>
        <input type="submit" value="Scan input files for duplicates"/>
    </form>

</body>
</html>