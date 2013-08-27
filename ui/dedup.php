<?php
include_once("common.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title></title>

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
                    html += "<label>Try the filters:</label>";
                    html += "<input class='try-filters' type='text'/>";
                    html += "=&gt;";
                    html += "<input class='result-filters' type='text' disabled/>";

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
                function updateFiltersResult(event){
                    var text =  JSON.stringify($(event.target).val());

                    var ul = $(event.target).parent().find(".filter-list");
                    var filters = JSON.stringify(getUlElements(ul));

                    var target = $(event.target).parent().find(".result-filters");

                    $.ajax({
                        url: 'tryFilters.php',
                        data: {
                            text: text,
                            filters: filters
                        },
                        success: function(data){
                            target.val(data);
                        }
                    });
                }

                $(".try-filters").on("keyup", updateFiltersResult);

                $(".filter-adder").unbind("click").on("click", function(){
                    var ul = $(this).parent().find(".filter-list");
                    var value = this.value;
                    addLi(ul, value);
                    updateFiltersResult({target: $(this).parent().parent().find(".try-filters")});
                });

                $(".filter-remover").unbind("click").on("click", function(){
                    var ul = $(this).parent().find(".filter-list");
                    removeLastLi(ul);
                    updateFiltersResult({target: $(this).parent().find(".try-filters")});
                });
            }
            setFilterEvents();

            $("#identifyingColumnEnabled").on("click", function(){
                var checked = $(this).is(':checked');
                if (checked) {
                    $(".identifyingColumn select").removeAttr('disabled');
                } else {
                    $(".identifyingColumn select").attr('disabled', 'disabled');
                }
            });

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
                    JSON.stringify(
                        $("#identifyingColumnEnabled").is(':checked')?
                            $(".identifyingColumn select").find(":selected").text():
                            ""
                    )
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
    <?php $current_page_URL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] ?>
    <?= getDupGroupsHTML($current_page_URL); ?>

    <hr>
    <h2>Configure scanner:</h2>

    <h3>Input files:</h3>
    <?= getInputFilesListHTML() ?>
    <h4>Input files preview:</h4>
    <?= getInputFilePreviewHTML(getInputFiles(), 3); ?>

    <hr>
    <h3>Identifying column (PURL):</h3>
    <div class="identifyingColumn">
        <input id="identifyingColumnEnabled" type="checkbox" checked="checked"/>
        <label for="identifyingColumnEnabled">Check uniqueness on the column: </label>
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