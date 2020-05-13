<?php 
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http" && $_SERVER['HTTP_HOST'] == "montage.webfurther.com") {
	header("Location: https://montage.webfurther.com/users/login");	
}
?>