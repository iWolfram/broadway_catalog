<?php
include ("../includes/header.php");

$from = $_GET['from'];

if (isset($_POST['submit'])) {

	$username = $_POST['user'];
	$password = $_POST['password'];
	
	if($username != "" && $password != ""){
		if($username == "foo" && $password == "bar"){
		session_start();
		$_SESSION['9vvh56nrtljefnj9ksk3223ft6'] = session_id();
		$_SESSION['login'] = true;
			switch ($from) {
				case 'home':
					header("Location:../index.php");
					break;
				case 'list':
					header("Location:../list.php");
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
		}else{
			$msg = "* Invalid login credentials";
		}
	}else{
		$msg = "* Please enter all fields";
	}
} // end of if isset submit
?>
<div class="well col-md-4 col-md-offset-4" style="border-radius: 5px;">
	<div class="message col-md-12 row">
		<?php if ($msg) { echo $msg; } ?>
	</div>
	<h1 class="text-center">Login</h1>
	<hr>
	<form id="loginForm" name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
			<div class="form-group input-group">
				<input type="text" name="user" class="form-control" placeholder="Username" style="border-radius: 5px 0 0 5px;">
				<span class="input-group-addon" style="background-color: rgba(119, 119, 119, 0.8); border-radius: 0 5px 5px 0;"><i class="glyphicon glyphicon-user"></i></span>
			</div>
			<div class="form-group input-group">
				<input type="password" name="password" class="form-control" placeholder="Password" style="border-radius: 5px 0 0 5px;">
				<span class="input-group-addon" style="background-color: rgba(119, 119, 119, 0.8); border-radius: 0 5px 5px 0;"><i class="glyphicon glyphicon-lock"></i></span>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" class="btn btn-info col-md-4" value="Login" style="border-radius: 5px;">
			</div>
	</form>
</div>

<?php
include ("../includes/footer.php");
?>