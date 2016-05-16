<?php 
session_start(); 
$usernames = $_SESSION['uid'];
		// Unset all the session variables
		$_SESSION = array();		
		// Destroy the session cookie
		if(isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		// Destroy the session
		session_destroy();
		header("Location: home");
?>