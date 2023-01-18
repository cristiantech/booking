<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_survey = "localhost";
$database_survey = "kimirina_survey";
$username_survey = "root";
$password_survey = "principe406!";
$survey = mysql_pconnect($hostname_survey, $username_survey, $password_survey) or trigger_error(mysql_error(),E_USER_ERROR); 
//mysql_query("SET NAMES 'utf8'");
//mysql_query("SET lc_time_names = 'es_ES'");
?>