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
  $insertSQL = sprintf("INSERT INTO users (name, type, username, password, email) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($insertSQL, $events_attendance) or die(mysql_error());

  $insertGoTo = "add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
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
	
<div class="inHeading">Create User</div>
	
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Name:</td>
        <td colspan="3"><input name="name" type="text" autofocus="autofocus" required="required" class="textbox2" id="name"></td>
      </tr>
      <tr>
        <td width="100">Username:</td>
        <td width="250"><input name="username" type="text" required="required" class="textbox2" id="username"></td>
        <td width="100" align="right">Password:</td>
        <td width="250"><input name="password" type="text" required="required" class="textbox2" id="password"></td>
      </tr>
      <tr>
        <td width="100">e-mail:</td>
        <td width="250"><input name="email" type="email" required="required" class="textbox2" id="email"></td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250" align="right"><input name="type" type="hidden" id="type" value="user">          <input type="submit" name="submit" id="submit" value="Add user"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>