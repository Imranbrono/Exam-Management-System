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

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM users WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_events_attendance, $events_attendance);
  $Result1 = mysql_query($deleteSQL, $events_attendance) or die(mysql_error());

  $deleteGoTo = "users.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_events_attendance, $events_attendance);
$query_users_view = "SELECT * FROM users";
$users_view = mysql_query($query_users_view, $events_attendance) or die(mysql_error());
$row_users_view = mysql_fetch_assoc($users_view);
$totalRows_users_view = mysql_num_rows($users_view);
?>
<?php include('file_1.php'); ?>

<div class="text-right">
<a href="users/add.php" class="fancybox fancybox.iframe"><button class="btn btn-danger">Create Users</button></a>
</div>


  <table width="100%" border="0" class="billList">
    <tbody>
      <tr>
        <th width="30" scope="col">SN</th>
        <th scope="col">Name</th>
        <th scope="col">User Type</th>
        <th width="140" scope="col">email</th>
        <th width="140" scope="col">Username</th>
        <th width="140" scope="col">Password</th>
        <th width="80" align="center" scope="col">&nbsp;</th>
      </tr>
		<?php $i = 1; ?>
        <?php do { ?>
		<?php $userType = $row_users_view['type']; ?>
      <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td><?php echo $row_users_view['name']; ?></td>
          <td><?php echo $row_users_view['type']; ?></td>
          <td width="140"><?php echo $row_users_view['email']; ?></td>
          <td width="140"><?php echo $row_users_view['username']; ?></td>
          <td width="140"><?php echo $row_users_view['password']; ?></td>
          <td width="80" align="center">
			  
			  <a href="users/edit.php?seno=<?php echo $row_users_view['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/edit.png" width="20" height="20" alt=""/></a>
			  
			  <?php if($userType == 'user'){ ?>
			  <a href="users.php?delete=<?php echo $row_users_view['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="support/buttons/delete.fw.png" width="20" height="20" alt=""/></a>
		  	<?php } ?>
		  </td>
      </tr><?php $i++; ?>
          <?php } while ($row_users_view = mysql_fetch_assoc($users_view)); ?>
    </tbody>
  </table>
  <?php include('file_2.php'); ?>
<?php
mysql_free_result($users_view);
?>
