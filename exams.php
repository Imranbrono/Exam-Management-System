<?php require_once('Connections/events_attendance.php'); ?>
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

$pagefull = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$end    = strpos($pagefull, '.php', 0);
$length = $end;
$pagefull = substr($pagefull, 0, $length +4);
?>
<?php include('file_1.php'); ?>

<div class="row">

<div class="col-md-4">
<a href="exam1.php?email=<?php echo $row_get_logged_user['email']; ?>&studentID=<?php echo $row_get_logged_user['seno']; ?>" target="_blank">
<div style="border:1px solid #CCC; padding:10px;">
<h1>Exam 1</h1>
</div>
</a>
</div>

<div class="col-md-4">
<a href="exam2.php?email=<?php echo $row_get_logged_user['email']; ?>&studentID=<?php echo $row_get_logged_user['seno']; ?>" target="_blank">
<div style="border:1px solid #CCC; padding:10px;">
<h1>Exam 2</h1>
</div>
</a>
</div>

<div class="col-md-4">
<a href="exam3.php?email=<?php echo $row_get_logged_user['email']; ?>&studentID=<?php echo $row_get_logged_user['seno']; ?>" target="_blank">
<div style="border:1px solid #CCC; padding:10px;">
<h1>Exam 3</h1>
</div>
</a>
</div>

</div>

<?php include('file_2.php'); ?>
