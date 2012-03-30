<?php if(!defined('SANITY_CHECK')) die('You cannot access this directly!');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DATABASE', 'sendspace');

define('DB_MEMBERS_TABLE','sendspace_members'); 

define('SESSION_NAME', 'sendspaceSession');

//timeout cookie - 1 week
define('SESSION_TIMEOUT', 7*24*60*60);

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Unable to establish connection to the DB');
mysql_select_db(DB_DATABASE, $link);
mysql_query("SET names UTF8");