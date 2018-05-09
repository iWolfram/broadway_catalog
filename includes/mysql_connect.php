<?php

// Connect to DB: Since all files depend on this, this will be included in our header, which is then included in all files.
$con = mysqli_connect("localhost", "db_username","db_password","db_name");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//This stops SQL Injection in POST vars 
  foreach ($_POST as $key => $value) { 
    if(!is_array($_POST)){
      $_POST[$key] = mysqli_real_escape_string($con,$value);
    } 
  } 

  //This stops SQL Injection in GET vars 
  foreach ($_GET as $key => $value) { 
    $_GET[$key] = mysqli_real_escape_string($con, $value); 
  }

// 	We use a constant (basically the same as a variable (stores a value)) to set the Base URL. We will use this to resolve all links as absolute URL's instead of relative URL's. If we do this correct, then we will not have any troubles pathing to all our files.
  // Make sure the path starts with http:// and ends with the trailing slash
  // If you copy/paste this script into other projects and fail to change this, then you may have links going back to the former project. Copy/Paste/FAIL !
  define("BASE_URL", "http://rresuena1.dmitstudent.ca/dmit2025/broadway_catalog/");
  


  // WE can use define the global error handling. This will vary between different web servers you may encounter. You may be able to set this using the "php.ini" file (if you have access), but you can also define it in your code.

  //error_reporting(E_ALL);// will show unindexed variables; you can declare them first (if(isset($myvar))) to remove this error.
  //error_reporting(E_ALL ^ E_NOTICE); to report everything except notice. Somewhat sloppy, but minimizes the work.
  error_reporting(E_ALL ^ E_NOTICE);
?>