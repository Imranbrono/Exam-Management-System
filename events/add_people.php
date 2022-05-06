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
  $insertSQL = sprintf("INSERT INTO people_invited (event_id, name, phone) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['event_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['phone'], "text"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($insertSQL, $events_attendance) or die(mysql_error());

  $insertGoTo = "add_people.php?eventx=".$_POST['event_id'];
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_event_info = "-1";
if (isset($_GET['eventx'])) {
  $colname_event_info = $_GET['eventx'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_event_info = sprintf("SELECT * FROM events WHERE seno = %s", GetSQLValueString($colname_event_info, "int"));
$event_info = mysql_query($query_event_info, $events_attendance) or die(mysql_error());
$row_event_info = mysql_fetch_assoc($event_info);
$totalRows_event_info = mysql_num_rows($event_info);

$colname_invitees_view = "-1";
if (isset($_GET['eventx'])) {
  $colname_invitees_view = $_GET['eventx'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_invitees_view = sprintf("SELECT * FROM people_invited WHERE event_id = %s ORDER BY seno DESC", GetSQLValueString($colname_invitees_view, "int"));
$invitees_view = mysql_query($query_invitees_view, $events_attendance) or die(mysql_error());
$row_invitees_view = mysql_fetch_assoc($invitees_view);
$totalRows_invitees_view = mysql_num_rows($invitees_view);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/style_in.css">
</head>

<body>
	
<div class="inHeading">Invitees<br>
<small><?php echo $row_event_info['name']; ?> | <?php echo $row_event_info['date']; ?> <?php echo $row_event_info['date']; ?> | <?php echo $row_event_info['venue']; ?></small>	
</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Invitee Name:</td>
        <td colspan="3"><input name="name" type="text" autofocus="autofocus" class="textbox2" id="name"></td>
      </tr>
      <tr>
        <td width="100">Phone:</td>
        <td width="250"><input name="phone" type="number" class="textbox2" id="phone"></td>
        <td width="100">&nbsp;</td>
        <td width="250" align="right"><input name="event_id" type="hidden" id="event_id" value="<?php echo $row_event_info['seno']; ?>">          <input type="submit" name="submit" id="submit" value="Invite"></td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
	
	
<table width="100%" border="0" class="billList">
  <tbody>
    <tr>
      <th width="30" scope="col">SN</th>
      <th scope="col">Invitees</th>
      <th width="150" scope="col">Phone</th>
      <th width="45" align="center" scope="col">&nbsp;</th>
    </tr>
	  <?php $i = 1; ?>
      <?php do { ?>
    <tr>
        <td width="30" align="center"><?php echo $i; ?></td>
        <td><?php echo $row_invitees_view['name']; ?></td>
        <td width="150"><?php echo $row_invitees_view['phone']; ?></td>
        <td width="45" align="center"><img src="../support/buttons/edit.png" width="20" height="20" alt=""/> <img src="../support/buttons/delete.fw.png" width="20" height="20" alt=""/></td>
    </tr><?php $i++; ?>
        <?php } while ($row_invitees_view = mysql_fetch_assoc($invitees_view)); ?>
  </tbody>
</table>
	
</body>
</html>
<?php
mysql_free_result($event_info);

mysql_free_result($invitees_view);
?>
