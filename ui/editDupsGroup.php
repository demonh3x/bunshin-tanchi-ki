<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">

        // Execute--------------------------------------------------------------------------------------------

        <?php
            include_once ("readDupsGroup.php");
            $objectGetArray = new Arrays();
        ?>

        var arrayPHPToJavascript = convertPHPArrayToJavascript( <?php echo json_encode($objectGetArray->getArrayRows()) ?> );
        var arrayPURLs = convertPHPArrayToJavascript( <?php echo json_encode($objectGetArray->getArrayPURLs()) ?> );

        writeForm();

        
        var purlColumn = $("#list_of_duplicates tr:gt(0) td:nth-child(" + getPurlColumnIndex() + ") input[type=text]");


        $(document).ready(function(){
            purlColumn.each(function(){
                checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexes());
            });
            console.log("--------------------------------------------------------------------------------------------");
        });

        /*var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();*/

        purlColumn.keyup(function(){
            /*delay(function(){*/
                purlColumn.each(function(){
                    checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexes());
                });
                console.log("--------------------------------------------------------------------------------------------");
            /*}, 1500 );*/
        });

        $("#purlColumnName").change(function(){
            purlColumn = $("#list_of_duplicates tr:gt(0) td:nth-child(" + getPurlColumnIndex() + ") input[type=text]");
            purlColumn.each(function(){
                checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexes());
            });
            console.log("--------------------------------------------------------------------------------------------");
        })


        // Functions--------------------------------------------------------------------------------------------

        function convertPHPArrayToJavascript ( phpArrayAsString )
        {
            var arrayInJavascript = JSON.parse(phpArrayAsString);
            return arrayInJavascript;
        }

        function convertJavascriptArrayToPHP(array){
            document.write("<form action=\"saveDupsGroup.php\" method=post name=sendArrayToPHP>" +
                "<input id=\"arrayAsString\" name=\"arrayAsString\" type=hidden>" +
                "</form>");

            var arv = JSON.stringify(array);
            document.sendArrayToPHP.arrayAsString.value = arv;
            document.sendArrayToPHP.submit();
        }

        function writeForm () {
            // Show the arrayPHPToJavascript Array in a table
            document.write("<form name=\"duplicates\"><table id=list_of_duplicates border=1>");

            document.write(     "<tr><td style=\"background-color: grey;\"></td>");

            for (var columnName in arrayPHPToJavascript[0])
            {
                document.write(     "<td id style=\"color: white; width: 100px; text-align: center; font-weight: 900; background-color: grey;\">" +
                                        "<span>" + columnName + "</span>" +
                                    "</td>");
            }
            document.write(     "</tr>");

            for (var i = 0; i < arrayPHPToJavascript.length; i++)
            {
                document.write( "<tr>" +
                                    "<td style=\"width: 25px; text-align: center; background-color: grey;\">" +
                                        "<input type=checkbox name=\"checkboxlist\" />" +
                                    "</td>");

            for (var k in arrayPHPToJavascript[i])
            {
                document.write(     "<td style=\"width: 100px;\">" +
                                        "<input type=text value=\""+ arrayPHPToJavascript[i][k] +"\">" +
                                    "</td>");
            }
            document.write(     "</tr>");
            }


            document.write( "</table>");

            var totalColumns = $("#list_of_duplicates tr:nth-child(1)").children().length;

            document.write("Purl Column Name:" +
                                "<select id=\"purlColumnName\" >");
            document.write(         "<option selected> CHOOSE </option>");
            for (var i = 2; i < totalColumns; i++)
            {
                var actualColumnName = $("#list_of_duplicates tr:nth-child(1) td:nth-child(" + i + ") span").text();
                document.write(     "<option>" + actualColumnName + "</option>");
            }
            document.write(     "</select><br>" +

                            "<input type=\"button\" value=\"Show me checked with current values\"" +
                            " onclick=sendCheckedRowsToPHP()>" +
                            "<input type=\"button\" value=\"Check if ready\" onclick=checkIfReadyToSend()>" +
                        "</form>");
        }

        function getPurlColumnIndex() {
            var selectedPurl = $("#purlColumnName option:selected").index() + 1;

            console.log(selectedPurl);


            return selectedPurl;
        }

        function sendCheckedRowsToPHP(){
            var checkedCheckboxes = $("input[type=checkbox]:checked");
            var arrayRow = new Array();

            var arrayColumnTitles = new Array();

            for (var titleText in arrayPHPToJavascript[0])
            {
                arrayColumnTitles.push(titleText);
            }

            checkedCheckboxes.each(function(){
                var columnsInSelectedRow = $(this).parent().parent().find("input[type=text]");
                var arrayColumns = {};
                columnsInSelectedRow.each ( function(indexCols, element) {
                        arrayColumns[arrayColumnTitles[indexCols]] = ($(element).val());
                        console.log(arrayColumns);
                })
                arrayRow.push(arrayColumns);
            })
            console.log(arrayRow);
            convertJavascriptArrayToPHP(arrayRow);
        }

        function getArrayOfRepeatedIndexes () {
            var arrayRepeatedIndexes = new Array();

            purlColumn.each(function(){
                arrayRepeatedIndexes[$(this).val()] = new Array();
            });

            purlColumn.each(function(){
                if ($(this).val() in arrayRepeatedIndexes)
                {
                    console.log("Index of " + $(this).val() + " is " + $(this).parent().parent().index());
                    arrayRepeatedIndexes[$(this).val()].push($(this).parent().parent().index() + 1);
                }

                console.log("LENGTH OF " + $(this).val() + " SUBARRAY ->" + arrayRepeatedIndexes[$(this).val()].length);
            });

            return arrayRepeatedIndexes;
        }

        function checkIfPURLIsBeingUsed(element, arrayModifyingPURLs){
            var purlUsed = false;

            purlColumn.each(function(){
                if (    ($(element).val() in arrayPURLs)
                    ||  (arrayModifyingPURLs[$(element).val()].length > 1)
                    ||  ($(element).val() == ""))
                {
                    for (var value in arrayModifyingPURLs[$(element).val()])
                    {
                        var rowNum = arrayModifyingPURLs[$(element).val()][value];

                        console.log("INDEXES TO RED ->" + arrayModifyingPURLs[$(element).val()][value]);
                        $("#list_of_duplicates tr:nth-child(" + rowNum + ")" +
                            " td:nth-child(" + getPurlColumnIndex() + ") input[type=text]").css("background", "red");
                    }

                    console.log("--" + $(element).val() + "-- is ALREADY defined.");
                    purlUsed = true;
                }
                else
                {
                    console.log("--" + $(element).val() + "-- is NOT yet defined.");
                    $(element).css("background", "lightgreen");
                }

            });

            console.log(purlUsed);
            return purlUsed;
        }

        function checkIfReadyToSend () {
            var readyToSave = true;

            purlColumn.each(function(){
                if (checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexes())){
                    readyToSave = false;
                }
            });

            if (readyToSave)
            {
                alert("You can send the file. There are no PURL duplicates.")
            }
            else
            {
                alert("You can not send the file. Duplicates were found. They are highlighted in red.")
            }
        }


    </script>
</html>
