<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_booking = "localhost";
$database_booking = "booking";
$username_booking = "root";
$password_booking = "principe406!";
$booking = mysql_pconnect($hostname_booking, $username_booking, $password_booking) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'utf8'");
mysql_query("SET lc_time_names = 'es_ES'");
?>