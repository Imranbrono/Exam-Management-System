<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
error_reporting(E_ALL ^ E_DEPRECATED);
//error_reporting(0);
$hostname_events_attendance = "localhost";
$database_events_attendance = "lms_new";
$username_events_attendance = "root";
$password_events_attendance = "";
$events_attendance = mysql_pconnect($hostname_events_attendance, $username_events_attendance, $password_events_attendance) or trigger_error(mysql_error(),E_USER_ERROR); 
?>