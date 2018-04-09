<?php
require_once('../includes/functions.php');
require_once('../includes/session.php');

if($session->is_logged_in()) 	$session->logmeout();
redirect_to("index.php");
?>