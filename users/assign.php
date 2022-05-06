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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET assigned_event=%s WHERE seno=%s",
                       GetSQLValueString($_POST['assigned_event'], "int"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($updateSQL, $events_attendance) or die(mysql_error());

  $updateGoTo = "assign.php?user=".$_POST['seno'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_user_info = "-1";
if (isset($_GET['user'])) {
  $colname_user_info = $_GET['user'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_user_info = sprintf("SELECT * FROM users WHERE seno = %s", GetSQLValueString($colname_user_info, "int"));
$user_info = mysql_query($query_user_info, $events_attendance) or die(mysql_error());
$row_user_info = mysql_fetch_assoc($user_info);
$totalRows_user_info = mysql_num_rows($user_info);

mysql_select_db($database_events_attendance, $events_attendance);
$query_load_ongoing_events = "SELECT * FROM events WHERE status IS NULL";
$load_ongoing_events = mysql_query($query_load_ongoing_events, $events_attendance) or die(mysql_error());
$row_load_ongoing_events = mysql_fetch_assoc($load_ongoing_events);
$totalRows_load_ongoing_events = mysql_num_rows($load_ongoing_events);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/style_in.css">
<script type="text/javascript" src="../support/jquery-1.7.1.min.js"> </script>
</head>

<body>
	
<div class="inHeading">Assing User to Event</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">User:</td>
        <td width="250"><strong><?php echo $row_user_info['name']; ?></strong></td>
        <td width="100" align="right">Event:</td>
        <td width="250">
            <select name="assigned_event" class="textbox2" id="assigned_event">
              <option></option>
				<?php do { ?>
              <option value="<?php echo $row_load_ongoing_events['seno']; ?>"><?php echo $row_load_ongoing_events['name']; ?> | <?php echo $row_load_ongoing_events['date']; ?> <?php echo $row_load_ongoing_events['date']; ?> | <?php echo $row_load_ongoing_events['venue']; ?></option>
            <?php } while ($row_load_ongoing_events = mysql_fetch_assoc($load_ongoing_events)); ?>
            </select>
		  
<script type="text/javascript">
jQuery(document).ready(function($){
$('#assigned_event').find('option[value="<?php echo $row_user_info['assigned_event']; ?>"]').attr('selected','selected');
});
</script> 
		  
			
		  </td>
      </tr>
      <tr>
        <td width="100">Username:</td>
        <td width="250"><strong><?php echo $row_user_info['username']; ?></strong></td>
        <td width="100" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_user_info['seno']; ?>"></td>
        <td width="250" align="right"><input type="submit" name="submit" id="submit" value="Assign Event"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($user_info);

mysql_free_result($load_ongoing_events);
?>
