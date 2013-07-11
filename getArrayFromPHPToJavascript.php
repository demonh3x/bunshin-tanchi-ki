<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>

    <body>
    </body>

    <script type="text/javascript">

        /*function saveModifiedDuplicates (

        )*/

        var arrayPHPToJavascript = new Array();


        <?php

        include_once ("sendArrayFromPHPToJavascript.php");
        $objectGetArray = new Arrays();

        $array_php = $objectGetArray->getArray();

        echo "\narrayPHPToJavascript = [";
        for ($i = 0, $totalArrays = count($array_php); $i < $totalArrays; $i ++)
        {
            echo "[";
            for($j = 0, $totalValues = count($array_php[$i]); $j < $totalValues; $j ++)
            {
                echo "\"".$array_php[$i][$j]."\"";
                if ($j != $totalValues-1)
                {
                    echo ", ";
                }
            }
            echo "]";
            if ($i != $totalArrays-1)
            {
                echo ", ";
            }
        }
        echo "];";
        ?>


        document.write("<form name=\"duplicates\" onSubmit=saveModifiedDuplicates()><table border=1>");

            //Reads the Javascript resulting array
            document.write("<tr><td style=\"background-color: grey;\"></td>");

            for (var column = 0; column < arrayPHPToJavascript.length; column++)
            {
                document.write("<td id style=\"color: white; width: 100px; text-align: center; font-weight: 900; background-color: grey;\">" +
                    "Column " + (column+1) + "</td>");
            }
            document.write("</tr>");

            for (var k = 0; k < arrayPHPToJavascript.length; k++)
            {
                document.write("<tr>" + "<td style=\"width: 25px; text-align: center; background-color: grey;\">" +
                    "<input type=checkbox>" + "</td>");

                for (var i = 0; i < arrayPHPToJavascript[k].length; i++)
                {
                    document.write("<td style=\"width: 100px;\">" +
                        "<input type=text value=\""+ arrayPHPToJavascript[k][i] +"\"></td>");
                }
                document.write("</tr>");
            }

        document.write("</table>" +
            "<input type=\"submit\" value=\"Save Modified Duplicates\"" +
            "</form>");



        document.write("<hr><h3>Modified rows</h3>")

        /*for (var k = 0; k < modifiedArray.length; k++)
        {
            for (var i = 0; i < modified[k].length; i++)
            {
                document.write(modifiedArray[k][i] + " - ");
            }
        }*/

    </script>
</html>
