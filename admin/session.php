<?php
ob_start();
//session_save_path("mysessions");
session_start();
	function logged_in() {
		return isset($_SESSION['uid']);
	}
    function isAdmin() {
		return isset($_SESSION['uid']);
	}
	function confirm_logged_in(){
		if (!logged_in()){
			header("Location: home");
		}
	}
?>
