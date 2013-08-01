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
    include_once ("common.php");
    $objectGetArray = new Arrays();
?>

var arrayPHPToJavascript = convertPHPArrayToJavascript( <?php echo json_encode($objectGetArray->getArrayRows()) ?> );
var arrayPURLs = convertPHPArrayToJavascript( <?php echo json_encode($objectGetArray->getArrayPURLs()) ?> );

writeForm();

var purlColumn = "";

$(document).ready(function(){
    purlColumn = $("#list_of_duplicates tr:gt(0) td:nth-child(" + getPurlColumnIndex() + ") input[type=text]");
    purlColumn.each(function(){
        checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexesInTheHoleTable());
    });
    console.log("--------------------------------------------------------------------------------------------");
});

// Event used to detect a change in a field (keyup, keydown, keypress, copy and paste with the mouse...
$.event.special.inputchange = {
    setup: function() {
        var self = this, val;
        $.data(this, 'timer', window.setInterval(function() {
            val = self.value;
            if ( $.data( self, 'cache') != val ) {
                $.data( self, 'cache', val );
                $( self ).trigger( 'inputchange' );
            }
        }, 20));
    },
    teardown: function() {
        window.clearInterval( $.data(this, 'timer') );
    },
    add: function() {
        $.data(this, 'cache', this.value);
    }
};

$("#purlColumnName").change(function(){
    purlColumn = $("#list_of_duplicates tr:gt(0) td:nth-child(" + getPurlColumnIndex() + ") input[type=text]");
    purlColumn.on('inputchange', function(){
        purlColumn.each(function(){
            checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexesInTheHoleTable());
        });
        console.log("--------------------------------------------------------------------------------------------");
    });
    $("#list_of_duplicates tr:gt(0) td:gt(0) input[type=text]").css("background", "white");
    purlColumn.each(function(){
        checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexesInTheHoleTable());
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
    <?php
        $dupsGroupPath = $_REQUEST["dupsGroup"];
        $separateDupsGroupPath = explode(__DUPLICATES_FOLDER__, $dupsGroupPath);

        $dedupDir = substr($separateDupsGroupPath[0], 0, -1);
        $_REQUEST["dir"] = $dedupDir;

        $uniquesFilePath = getUniquesFile();
    ?>
    document.write("<form action=\"saveDupsGroup.php\" method=\"post\" name=\"sendArrayToPHP\">" +
        "<input type=\"hidden\" name=\"uniquesFilePath\" value=\"<?= $uniquesFilePath ?>\">" +
        "<input type=\"hidden\" name=\"dupsGroupFilePath\" value=\"<?= $dupsGroupPath ?>\">" +
        "<input type=\"hidden\" name=\"identifyingColumn\" value=\"" + getPurlColumnName() + "\">" +
        "<input type=\"hidden\" id=\"arrayAsString\" name=\"arrayAsString\">" +
        "</form>");

    var arv = JSON.stringify(array);
    document.sendArrayToPHP.arrayAsString.value = arv;
    document.sendArrayToPHP.submit();
}

function writeForm () {
    document.write("<form name=\"duplicates\"><table id=list_of_duplicates border=1>");

    document.write(     "<tr><td style=\"background-color: grey;\">" +
        "<input type=\"checkbox\"  checked=\"checked\" name=\"select_all\">" +
        "</td>");

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
            "<input type=checkbox  checked=\"checked\" name=\"checkboxlist\" />" +
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

        "<input type=\"button\" value=\"Merge To Uniques File\"" +
        " onclick=sendCheckedRowsToPHP()>" +
        "</form>");
}

$("input[name=select_all]").change(function(){
    var c = this.checked;
    $(':checkbox').prop('checked', c);
});

function getPurlColumnIndex() {
    var selectedPurl = $("#purlColumnName option:selected").index() + 1;

    console.log(selectedPurl);

    return selectedPurl;
}

function getPurlColumnName(){
    return $("#list_of_duplicates tr:nth-child(1) td:nth-child(" + getPurlColumnIndex() + ") span").text();
}

function sendCheckedRowsToPHP(){
    var checkedCheckboxes = $("input[type=checkbox][name=checkboxlist]:checked");
    var checkedRowsNumber = 0;
    var purlExists = false;
    var purlRepeated = false;
    var purlEmpty = false;
    var arrayAllPURLSInTheCheckedRows = getArrayAllPURLSInTheCheckedRows();

    checkedCheckboxes.each(function(){
        checkedRowsNumber = checkedRowsNumber + 1;
        var thisCheckedRowsIndex = $(this).parent().parent().index() + 1;
        console.log("Fila:" + thisCheckedRowsIndex);
        var element = $("#list_of_duplicates tr:nth-child(" + thisCheckedRowsIndex + ") td:nth-child(" + getPurlColumnIndex() + ") input[type=text]").val();
        console.log("Elemento --> " + element);
        if (checkIfPURLExists(element))
        {
            purlExists = true;
        }
        if (checkIfPURLIsRepeatedInTable(element, arrayAllPURLSInTheCheckedRows))
        {
            purlRepeated = true;
        }
        if (checkIfPURLIsEmpty(element))
        {
            purlEmpty = true;
        }
    });
    console.log("purlExists --> " + purlExists);
    console.log("purlRepeated --> " + purlRepeated);

    /*var readyToSave = true;
     purlColumn.each(function() {
     //$(this).parent().parent().find("td:nth-child(" + getPurlColumnIndex() + ")").css("background", "black");
     if (checkIfPURLIsBeingUsed(this, getArrayOfRepeatedIndexesInTheHoleTable())){
     readyToSave = false;
     }
     });*/


    var purlColumnSelected = false;
    if (getPurlColumnIndex() != 1)
    {
        purlColumnSelected = true;
    }


    if (purlColumnSelected == false)
    {
        alert("There is any Purl Column selected to check.");
    }
    else if (checkedRowsNumber == 0)
    {
        alert("There are no checked rows to save.");
    }
    else if (purlEmpty)
    {
        alert("You can not send the file. There is at least one empty PURL checked to send.")
    }
    else if (purlExists)
    {
        alert("You can not send the file. Duplicates or empty purls were found.");
    }
    else if (purlRepeated)
    {
        alert("You can not send the file. There are two or more repeated PURLs " +
            "in the table.")
    }
    else
    {
        checkedCheckboxes = $("tr:gt(0)").find("input[type=checkbox]:checked");
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
            })
            arrayRow.push(arrayColumns);
        })
        console.log(arrayRow);
        convertJavascriptArrayToPHP(arrayRow);
    }
}

function getArrayOfRepeatedIndexesInTheHoleTable () {
    var arrayRepeatedIndexesInTheHoleTable = new Array();

    purlColumn.each(function(){
        arrayRepeatedIndexesInTheHoleTable[$(this).val()] = new Array();
    });

    purlColumn.each(function(){
        if ($(this).val() in arrayRepeatedIndexesInTheHoleTable)
        {
            console.log("Index of " + $(this).val() + " is " + $(this).parent().parent().index());
            arrayRepeatedIndexesInTheHoleTable[$(this).val()].push($(this).parent().parent().index() + 1);
        }

        console.log("LENGTH OF " + $(this).val() + " SUBARRAY ->" + arrayRepeatedIndexesInTheHoleTable[$(this).val()].length);
    });

    return arrayRepeatedIndexesInTheHoleTable;
}

function getArrayAllPURLSInTheCheckedRows () {
    var arrayAllPURLSInTheCheckedRows = new Array();
    var checkedCheckboxes = $("input[type=checkbox][name=checkboxlist]:checked");

    checkedCheckboxes.each(function(){
        var thisCheckedRowsIndex = $(this).parent().parent().index() + 1;
        var element = $("#list_of_duplicates tr:nth-child(" + thisCheckedRowsIndex + ") td:nth-child(" + getPurlColumnIndex() + ") input[type=text]");

        arrayAllPURLSInTheCheckedRows.push(element.val());
    });

    console.log("ARRAY --> " + arrayAllPURLSInTheCheckedRows);
    return arrayAllPURLSInTheCheckedRows;
}

function checkIfPURLIsRepeatedInTable (element, arrayAllPURLSInTheCheckedRows)
{
    repeated = false;
    times = 0;

    for( var value in arrayAllPURLSInTheCheckedRows)
    {
        if (arrayAllPURLSInTheCheckedRows[value] == element)
        {
            times = times + 1;
        }
    }
    if (times > 1)
    {
        repeated = true;
    }

    return repeated;
}

function checkIfPURLIsEmpty (element) {
    empty = false;

    if (element == "")
    {
        empty = true;
    }

    return empty;
}

function checkIfPURLExists (element) {
    existing = false;
    if ( element in arrayPURLs )
    {
        existing = true;
    }

    return existing;
}

var red = false;

function checkIfPURLIsBeingUsed(element, arrayModifyingPURLs){
    red = false;

    if ( ($(element).val() in arrayPURLs) )
    {
        $(element).css("background","red");

        console.log("--" + $(element).val() + "-- is ALREADY defined as a PURL or is empty.");
        red = true;
    }
    else if( (arrayModifyingPURLs[$(element).val()].length > 1)
        ||  ($(element).val() == ""))
    {
        for (var value in arrayModifyingPURLs[$(element).val()])
        {
            var rowNum = arrayModifyingPURLs[$(element).val()][value];

            console.log("INDEXES TO RED ->" + arrayModifyingPURLs[$(element).val()][value]);
            $("#list_of_duplicates tr:nth-child(" + rowNum + ")" +
                " td:nth-child(" + getPurlColumnIndex() + ") input[type=text]").css("background", "orange");
        }

        console.log("--" + $(element).val() + "-- is repeated in other row of the same table.");
    }
    else
    {
        console.log("--" + $(element).val() + "-- is NOT yet defined.");
        $(element).css("background", "lightgreen");
    }
}

</script>
</html>
