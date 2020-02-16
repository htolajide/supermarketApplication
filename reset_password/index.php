
<?php
	error_reporting( E_ALL );
	ini_set( "display_errors", 1 );
	include('../includes/db.inc.php');	
		//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	include '../reset_password/password_reset.html.php';
?>
