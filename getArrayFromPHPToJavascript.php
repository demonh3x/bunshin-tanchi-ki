<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>

    <body>
    </body>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        <?php
            include_once ("sendArrayFromPHPToJavascript.php");
            $objectGetArray = new Arrays();
        ?>

        var arrayPHPToJavascript = new Array();
        arrayPHPToJavascript = JSON.parse( <?php echo json_encode($objectGetArray->getArray()) ?> );



        // Transform the arrayPHPToJavascript Array to a table
        document.write("<form name=\"duplicates\" onSubmit=saveModifiedDuplicates()><table id=list_of_duplicates border=1>");

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
            "<input type=\"button\" value=\"Show me checked\" onclick=getCheckboxValue()>" +
            "<input type=\"submit\" value=\"Save Modified Duplicates\"" +
            "</form>");



        document.write("<hr><h3>Modified rows</h3>")


        function getCheckboxValue(){
            var checkedCheckboxes = $("input[type=checkbox]:checked");
            var arrayRow = new Array();

            var arrayColumnTitles = new Array();

            for (var titleText in arrayPHPToJavascript[0])
            {
                arrayColumnTitles.push(titleText);
            }

            checkedCheckboxes.each(function(indexRow){
                var columnsInSelectedRow = $(this).parent().parent().find("input[type=text]");
                var arrayColumns = {};
                columnsInSelectedRow.each ( function(indexCols, element) {
                        arrayColumns[arrayColumnTitles[indexCols]] = ($(element).val());
                        console.log(arrayColumns);
                })
                arrayRow.push(arrayColumns);
            })
            console.log(arrayRow);
        }


    </script>
</html>
