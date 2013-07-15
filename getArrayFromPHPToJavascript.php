<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        <?php
            include_once ("sendArrayFromPHPToJavascript.php");
            $objectGetArray = new Arrays();
        ?>

        var arrayPHPToJavascript = new Array();
        arrayPHPToJavascript = JSON.parse( <?php echo json_encode($objectGetArray->getArrayRows()) ?> );
        var arrayPURLs = new Array();
        arrayPURLs = JSON.parse( <?php echo json_encode($objectGetArray->getArrayPURLs()) ?> );

        // Show the arrayPHPToJavascript Array in a table
        document.write("<form name=\"duplicates\"><table id=list_of_duplicates border=1>");

            document.write("<tr><td style=\"background-color: grey;\"></td>");

            for (var columnName in arrayPHPToJavascript[0])
            {
                document.write("<td id style=\"color: white; width: 100px; text-align: center; font-weight: 900; background-color: grey;\"><span>" +
                columnName + "</span></td>");
            }
            document.write("</tr>");

        for (var i = 0; i < arrayPHPToJavascript.length; i++)
            {
                document.write("<tr>" + "<td style=\"width: 25px; text-align: center; background-color: grey;\">" +
                    "<input type=checkbox name=\"checkboxlist\" />" + "</td>");

                for (var k in arrayPHPToJavascript[i])
                {
                    document.write("<td style=\"width: 100px;\">" +
                        "<input type=text value=\""+ arrayPHPToJavascript[i][k] +"\"></td>");
                }
                document.write("</tr>");
            }

        document.write("</table>" +
            "<input type=\"button\" value=\"Show me checked with current values\" onclick=getCheckedCurrentValue()>" +
            "<input type=\"button\" value=\"Ready to send?\" onclick=checkIfReadyToSend()>" +
            "</form>");

        function getCheckedCurrentValue(){
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

        function convertJavascriptArrayToPHP(array){
                document.write("<form action=\"getArrayFromJavascriptToPHP.php\" method=post name=sendArrayToPHP>" +
                                    "<input id=\"arrayAsString\" name=\"arrayAsString\" type=hidden>" +
                               "</form>");

                var arv = JSON.stringify(array);
                document.sendArrayToPHP.arrayAsString.value = arv;
                document.sendArrayToPHP.submit();
        }

        function transformAllRowsToArray () {
            var allButFirstRow = $("#list_of_duplicates tr:gt(0)".find("td");

        }


        var purlColumnIndex = 5;
        var purlColumn = $("#list_of_duplicates tr:gt(0) td:nth-child(" + purlColumnIndex + ") input[type=text]");
        var arrayModifiedPURLs = new Array();



        console.log(arrayModifiedPURLs);



        function checkIfPURLIsBeingUsed(element){
            var purlUsed = false;

            if ($(element).val() in arrayPURLs)
            {
                console.log("--" + $(element).val() + "-- is ALREADY defined.");
                $(element).css("background", "red");
                purlUsed = true;
            }
            else
            {
                console.log("--" + $(element).val() + "-- is NOT yet defined.");
                $(element).css("background", "lightgreen");
            }

            purlColumn.each(function(index){
                console.log(arrayModifiedPURLs[index]);
                if (arrayModifiedPURLs[index] == $(element).val());
                {
                    var repeatedPURLField = $("#list_of_duplicates tr:nth-child("+ index +") td:nth-child(" +
                                     purlColumnIndex + ") input[type=text]");
                    repeatedPURLField.css("background", "red");
                }
            });

            return purlUsed;
        }

        purlColumn.keyup(function(){
            checkIfPURLIsBeingUsed(this);

        });
        $(document).ready(function(){
            purlColumn.each(function(){
                checkIfPURLIsBeingUsed(this);
            });

        });


        function checkIfReadyToSend () {
            var readyToSave = true;

            purlColumn.each(function(){
                if (checkIfPURLIsBeingUsed(this)){
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

    <body>
        <?php var_dump($objectGetArray->getArrayPURLs()); ?>
    </body>
</html>
