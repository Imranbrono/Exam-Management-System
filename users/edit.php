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
  $updateSQL = sprintf("UPDATE users SET name=%s, type=%s, username=%s, password=%s WHERE seno=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
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

$colname_user_edit = "-1";
if (isset($_GET['seno'])) {
  $colname_user_edit = $_GET['seno'];
}
mysql_select_db($database_events_attendance, $events_attendance);
$query_user_edit = sprintf("SELECT * FROM users WHERE seno = %s", GetSQLValueString($colname_user_edit, "int"));
$user_edit = mysql_query($query_user_edit, $events_attendance) or die(mysql_error());
$row_user_edit = mysql_fetch_assoc($user_edit);
$totalRows_user_edit = mysql_num_rows($user_edit);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/style_in.css">
</head>

<body>
	
<div class="inHeading">Edit User</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Name:</td>
        <td colspan="3"><input name="name" type="text" autofocus="autofocus" required="required" class="textbox2" id="name" value="<?php echo $row_user_edit['name']; ?>"></td>
      </tr>
      <tr>
        <td width="100">Username:</td>
        <td width="250"><input name="username" type="text" required="required" class="textbox2" id="username" value="<?php echo $row_user_edit['username']; ?>"></td>
        <td width="100" align="right">Password:</td>
        <td width="250"><input name="password" type="text" required="required" class="textbox2" id="password" value="<?php echo $row_user_edit['password']; ?>"></td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_user_edit['seno']; ?>">
        <input name="type" type="hidden" id="type" value="<?php echo $row_user_edit['type']; ?>">          <input type="submit" name="submit" id="submit" value="Add user"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($user_edit);
?>
