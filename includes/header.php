<?php
session_start();
include("mysql_connect.php");// here we include the connection script; since this file(header.php) is included at the top of every page we make, the connection will then also be included. Also, config options like BASE_URL are also available to us.

// Login bool
if (!$_SESSION['login']) {
  $login = false;
}else{
  if ($_SESSION['login'] == false) {
    $login = false;
  }else{
    $login = true;
  }
}

// URL
$thisPath = htmlspecialchars($_SERVER['REQUEST_URI']);
$from = "";

switch ($thisPath) {
  case '/dmit2025/broadway_catalog/index.php':
    $from = "home";
    break;
  case '/dmit2025/broadway_catalog/list.php':
    $from = "list";
    break;
  default:
    $from = "home";
    break;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   

    <title>Broadway Catalog</title>

    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->

<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

 <!-- Your Custom styles for this project -->
 <!--  Note how we can use BASE_URL constant to resolve all links no matter where the file resides. -->
<link href="<?php echo BASE_URL ?>css/styles.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>/css/bootstrap-superhero.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <!--  We'll use the BASE_URL set in the connection script to resolve all links -->
            <a class="navbar-brand" href="<?php echo BASE_URL ?>index.php">Home</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <!-- This page doesn't exist. It's just a sample link. YOU need to change it! -->
              <li><a href="<?php echo BASE_URL ?>list.php">List</a></li>
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo BASE_URL ?>admin/insert.php">Insert</a></li>
                  <li><a href="<?php echo BASE_URL ?>admin/edit.php">Edit</a></li>
                  
                 
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><?php if ($login == false) {
                echo '<a href="'.BASE_URL.'admin/login.php?from='.$from.'">Login</a>';
              }else{
                echo '<a href="'.BASE_URL.'admin/logout.php?from='.$from.'" onclick="return confirm(\'Are you sure you want to log out?\')">Logout</a>';
              }?></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

    

      

    




