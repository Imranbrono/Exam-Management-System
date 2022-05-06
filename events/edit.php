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

$colname_edit_event = "-1";
if (isset($_GET['seno'])) {
  $colname_edit_event = $_GET['seno'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_edit_event = sprintf("SELECT * FROM events WHERE seno = %s", GetSQLValueString($colname_edit_event, "int"));
$edit_event = mysql_query($query_edit_event, $events_attendance) or die(mysql_error());
$row_edit_event = mysql_fetch_assoc($edit_event);
$totalRows_edit_event = mysql_num_rows($edit_event);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE events SET name=%s, `date`=%s, `time`=%s, venue=%s, status=%s WHERE seno=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['time'], "date"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($updateSQL, $events_attendance) or die(mysql_error());

  $updateGoTo = "../support/crude/updated.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
	
<div class="inHeading">Edit Event</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Name:</td>
        <td width="250"><input name="name" type="text" autofocus="autofocus" class="textbox2" id="name" value="<?php echo $row_edit_event['name']; ?>"></td>
        <td width="100" align="right">Venue:</td>
        <td width="250"><input name="venue" type="text" class="textbox2" id="venue" value="<?php echo $row_edit_event['venue']; ?>"></td>
      </tr>
      <tr>
        <td width="100">Date:</td>
        <td width="250"><input name="date" type="date" class="textbox2" id="date" value="<?php echo $row_edit_event['date']; ?>"></td>
        <td width="100" align="right">Time:</td>
        <td width="250"><input name="time" type="time" class="textbox2" id="time" value="<?php echo $row_edit_event['time']; ?>"></td>
      </tr>
      <tr>
        <td width="100">Status:</td>
        <td width="250"><select name="status" class="textbox2" id="staus">
			<option value="" <?php if($row_edit_event['status'] == 'NULL'){ echo 'selected'; } ?>>OnGoing</option>
			<option value="Finished" <?php if($row_edit_event['status'] == 'Finished'){ echo 'selected'; } ?>>Finished</option>
        </select></td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_edit_event['seno']; ?>">        <input type="submit" name="submit" id="submit" value="Save Changes"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($edit_event);
?>
