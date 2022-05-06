<?php require_once('../Connections/events_attendance.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO events (name, `date`, `time`, venue) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['time'], "date"),
                       GetSQLValueString($_POST['venue'], "text"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($insertSQL, $events_attendance) or die(mysql_error());

mysql_select_db($database_events_attendance, $events_attendance);
$query_last_event = "SELECT * FROM events ORDER BY seno DESC";
$last_event = mysql_query($query_last_event, $events_attendance) or die(mysql_error());
$row_last_event = mysql_fetch_assoc($last_event);
$totalRows_last_event = mysql_num_rows($last_event);

  $insertGoTo = "add_people.php?eventx=".$row_last_event['seno'];
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/style_in.css">
</head>

<body>
	
<div class="inHeading">Create Event</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Name:</td>
        <td width="250"><input name="name" type="text" autofocus="autofocus" class="textbox2" id="name"></td>
        <td width="100" align="right">Venue:</td>
        <td width="250"><input name="venue" type="text" class="textbox2" id="venue"></td>
      </tr>
      <tr>
        <td width="100">Date:</td>
        <td width="250"><input name="date" type="date" class="textbox2" id="date"></td>
        <td width="100" align="right">Time:</td>
        <td width="250"><input name="time" type="time" class="textbox2" id="time"></td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250" align="right"><input type="submit" name="submit" id="submit" value="Create Event"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>