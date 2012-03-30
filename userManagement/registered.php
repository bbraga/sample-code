<?php
define('SANITY_CHECK',true);
require dirname(__FILE__) . "/lib/config.php";
session_name(SESSION_NAME);
session_set_cookie_params(SESSION_TIMEOUT);
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>user login procedure</title>
    
    <link rel="stylesheet" type="text/css" href="demo.css" media="screen" />
    
</head>

<body>

<div id="main">
    <div class="container">
    
    <?php
	if($_SESSION['id']){
        echo '<h1>Hello, '.$_SESSION['usr'].'! You are registered and logged in!</h1>';    
    } else {
        echo '<h1>Please, <a href="index.php">login</a> and come back later!</h1>';
    } 
    ?>
    </div>
    
</div>


</body>
</html>
