<?php 
/**
* March 30th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
*/    
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DATABASE', 'sendspace');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Unable to establish connection to the DB');
mysql_select_db(DB_DATABASE, $link);
mysql_query("SET names UTF8");