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

$colname_event_info = "-1";
if (isset($_GET['seno'])) {
  $colname_event_info = $_GET['seno'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_event_info = sprintf("SELECT * FROM events WHERE seno = %s", GetSQLValueString($colname_event_info, "int"));
$event_info = mysql_query($query_event_info, $events_attendance) or die(mysql_error());
$row_event_info = mysql_fetch_assoc($event_info);
$totalRows_event_info = mysql_num_rows($event_info);

$colname_invitees_info = "-1";
if (isset($_GET['seno'])) {
  $colname_invitees_info = $_GET['seno'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_invitees_info = sprintf("SELECT * FROM people_invited WHERE event_id = %s", GetSQLValueString($colname_invitees_info, "int"));
$invitees_info = mysql_query($query_invitees_info, $events_attendance) or die(mysql_error());
$row_invitees_info = mysql_fetch_assoc($invitees_info);
$totalRows_invitees_info = mysql_num_rows($invitees_info);

mysql_select_db($database_events_attendance, $events_attendance);
$query_invitees_info_in = sprintf("SELECT * FROM people_invited WHERE event_id = %s AND received_user IS NOT NULL AND received_user <> 0", GetSQLValueString($colname_invitees_info, "int"));
$invitees_info_in = mysql_query($query_invitees_info_in, $events_attendance) or die(mysql_error());
$row_invitees_info_in = mysql_fetch_assoc($invitees_info_in);
$totalRows_invitees_info_in = mysql_num_rows($invitees_info_in);



?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/style_in.css">
</head>

<body>
	
<div class="inHeading">Invitees Attendance Summary
	<img src="../support/buttons/printx.fw.png"  onclick="myFunction()" class="no-print" width="50" height="30" alt="" style="float: right; clear: both; cursor: pointer; margin-top:-10px;" />
</div>
<script>
function myFunction() {
    window.print();
}
</script>
	
	
  <table width="100%" border="0" class="billList">
    <tbody>
      <tr>
        <th width="100">Name</th>
        <td width="250"><?php echo $row_event_info['name']; ?></td>
        <th width="100">Venue</th>
        <td width="250"><?php echo $row_event_info['venue']; ?></td>
      </tr>
      <tr>
        <th width="100">Date/Time</th>
        <td width="250"><?php echo $row_event_info['date']; ?> <?php echo $row_event_info['time']; ?></td>
        <th width="100">Attendance</th>
        <td width="250"><?php echo $totalRows_invitees_info_in; ?> of <?php echo $totalRows_invitees_info; ?></td>
      </tr>
    </tbody>
  </table>
	
<br>	
	
<table width="100%" border="0" class="billList">
  <tbody>
    <tr>
      <th width="30" scope="col">SN</th>
      <th scope="col">Name</th>
      <th width="100" scope="col">Phone</th>
      <th width="100" scope="col">Arrived on</th>
      <th width="120" scope="col">Received By</th>
    </tr>
	  <?php $i = 1; ?>
      <?php do { ?>
<?php
$userX = $row_invitees_info['received_user'];

mysql_select_db($database_events_attendance, $events_attendance);
$query_user_info_get = "SELECT * FROM users WHERE seno = '$userX'";
$user_info_get = mysql_query($query_user_info_get, $events_attendance) or die(mysql_error());
$row_user_info_get = mysql_fetch_assoc($user_info_get);
$totalRows_user_info_get = mysql_num_rows($user_info_get);
?>
    <tr>
        <td width="30" align="center"><?php echo $i; ?></td>
        <td><?php echo $row_invitees_info['name']; ?></td>
        <td width="100" align="center"><?php echo $row_invitees_info['phone']; ?></td>
		<?php if($userX != NULL AND $userX != 0){ ?>
        <td width="100" align="center"><?php echo $row_invitees_info['arrival_time']; ?></td>
        <td width="120"><?php echo $row_user_info_get['name']; ?></td>
		<?php } else{ ?>		
        <td colspan="2" align="center"> - absent - </td>
		<?php } ?>		
    </tr><?php $i++; ?>
        <?php } while ($row_invitees_info = mysql_fetch_assoc($invitees_info)); ?>
  </tbody>
</table>
	
</body>
</html>
<?php
mysql_free_result($event_info);

mysql_free_result($invitees_info);
?>
