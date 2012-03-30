<?php
/**
* Original Author URL: http://tutorialzine.com/2009/10/cool-login-system-php-jquery/
* Original Copyright Unknown.
* 
* March 29th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
* extensive changes made to original script, cleanup, added variables and functionality
* Purpose: Implement a user login procedure in PHP as if part of an existing web site
*/

// sanity check constant for requiring lib files
define('SANITY_CHECK',true);
require dirname(__FILE__) . "/lib/config.php";
require dirname(__FILE__) . "/lib/functions.php";

// Starting the session
session_name(SESSION_NAME);

// Making the cookie live for 1 week
session_set_cookie_params(SESSION_TIMEOUT);
session_start();

if($_SESSION['id'] && !isset($_COOKIE['sendspaceRemember']) && !$_SESSION['rememberMe']) {
	$_SESSION = array();
	session_destroy();
}

if(isset($_GET['logoff'])) {
	$_SESSION = array();
	session_destroy();	
	header("Location: index.php");
	exit;
}

if($_POST['submit']=='Login') {
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err)) {
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data

		$row = mysql_fetch_assoc(mysql_query("SELECT id, username FROM ". DB_MEMBERS_TABLE ." WHERE username='{$_POST['username']}' AND pass='".md5($_POST['password'])."'"));

		if($row['username']) { 
			// If everything is OK login
			
			$_SESSION['username'] = $row['username'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('sendspaceRemember', $_POST['rememberMe']);
		}
		else $err[]='Wrong username and/or password!';
	}
	
	if($err){
        $_SESSION['msg']['login-err'] = implode('<br />',$err);    
        $_SESSION['lastUser'] = $_POST['username'];
    }

	header("Location: index.php");
	exit;
}
else if( $_POST['submit'] == 'Register' )
{
	// If the Register form has been submitted
	$err = array();	
	if(strlen($_POST['username']) < 4 || strlen($_POST['username']) > 32 ) {
		$err[]='Your username must be between 3 and 32 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username'])) {
		$err[]='Your username contains invalid characters!';
	}
	
	if(!checkEmail($_POST['email'])) {
		$err[]='Your email is not valid!';
	}
	
	if(!count($err)) {
		// Generate a random password
		$pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);

		$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		
        $query = "INSERT INTO ". DB_MEMBERS_TABLE ."(username, pass, email, registration_ip, created_at)
                        VALUES(
                            '".$_POST['username']."',
                            '".md5($pass)."',
                            '".$_POST['email']."',
                            '".$_SERVER['REMOTE_ADDR']."',
                            NOW()
                        )";
		mysql_query($query);
		
		if(mysql_affected_rows($link)==1)
		{
			$_SESSION['msg']['reg-success']= $_POST['username'] . ': We have created a new user account for you with the password <b>' . $pass .'</b>';
		}
		else {
            $err[]='This username is already being used by someone else!';   
        }
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: index.php");
	exit;
}

$script = '';

if(!empty($_SESSION['msg'])) {
	// The script below shows the sliding panel on page load	
	$script = '
	<script type="text/javascript">	
		$(function(){		
			$("div#panel").show();
			$("#toggle a").toggle();
		});   
	</script>';	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Login</title>
    
    <link rel="stylesheet" type="text/css" href="demo.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="login_panel/css/slide.css" media="screen" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <script src="login_panel/js/slide.js" type="text/javascript"></script>
      
    <script type="text/javascript">    
        $(document).ready(function() {
            $("a#open").click();
        });     
    </script>        

    <?php echo $script; 
    if(!empty($_SESSION['id'])){
    ?>
        <meta http-equiv="Refresh" content="6; url=registered.php">
    <?php 
    }
    ?>    
</head>
<body>
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Sliding jQuery Panel</h1>
			</div>
            <?php
			if(empty($_SESSION['id'])) {
			?>
			<div class="left">
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    <?php
						
						if($_SESSION['msg']['login-err']) {
                            $loginError = true;
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					<label class="grey" for="username" value="<?php echo !empty($loginError) ? $_SESSION['lastUser'] : '' ?>">Username:</label>
					<input class="field" type="text" name="username" id="username" value="<?php echo !empty($loginError) ? $_SESSION['lastUser'] : '' ?>" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<form action="" method="post">
					<h1>Not a member yet? Sign Up!</h1>	                    
                    <?php
						if($_SESSION['msg']['reg-err']) {
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if($_SESSION['msg']['reg-success']) {
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>                    		
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
					<label>A password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>            
            <?php
			} else {
                //Logged in successfully
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            <a href="registered.php">View a special member page</a>
            <p>You will be redirected to the special member page in 6 seconds</p>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <p>You are successfully logged in and will be redirected to your members only page in 6 seconds.</p>
            <?php
			};
			?>
		</div>
	</div>
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo $_SESSION['username'] ? $_SESSION['username'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $_SESSION['id']?'Open Panel':'Log In | Register';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> 
</div> 
</body>
</html>