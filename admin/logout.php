<?php 
session_start();
// include ("../includes/header.php");
$from = $_GET['from'];
unset($_SESSION['9vvh56nrtljefnj9ksk3223ft6']);
$_SESSION['login'] = false;
switch ($from) {
	case 'home':
		header("Location:../index.php");
		break;
	case 'list':
		header("Location:../list.php");
		break;
	case 'search':
		header("Location:../search.php");
		break;
	case 'edit':
		header("Location:edit.php");
		break;
	case 'insert':
		header("Location:insert.php");
		break;
	default:
		header("Location:../index.php");
}

?>