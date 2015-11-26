<?php
session_start();
	
require_once('../includes/connect.php');
	
if(isset($_POST['submit'])){
    $errMsg = '';
    //username and password sent from Form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if($username == '')
        $errMsg .= 'You must enter your Username<br>';
		
    if($password == '')
        $errMsg .= 'You must enter your Password<br>';
		
    if($errMsg == ''){
        $records = $db->prepare("SELECT user_id, username, password FROM users WHERE username=:username");
        $records->bindParam(':username', $username);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
//		if(count($results) > 0 && password_verify($password, $results['password'])){
        if(count($results) > 0 && (md5($password) === $results['password'])){
            $_SESSION['user_id'] = $results['user_id'];
            header('location:../index.php');
            exit;
			} else {
                $errMsg .= 'Username and Password are not found<br>';
			}
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login Page PHP Script</title>
	<style type="text/css">
	body
	{
		font-family:Arial, Helvetica, sans-serif;
		font-size:14px;
	}
	label
	{
		font-weight:bold;
		width:100px;
		font-size:14px;
	}
	.box
	{
		border:1px solid #006D9C;
		margin-left:10px;
		width:60%;
	}
	.submit{
		border:1px solid #006D9C;
		background-color:#006D9C;
		color:#FFFFFF;
		float:right;
		padding:2px;
	}
	</style>
</head>
<body>
	
	<div align="center">
		<div style="width:300px; border: solid 1px #006D9C; " align="left">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
				}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:3px;"><b>Login</b></div>
			<div style="margin:30px">
				<form action="login.php" method="post">
					<label>Username  :</label><input type="text" name="username" class="box"/><br /><br />
					<label>Password  :</label><input type="password" name="password" class="box" /><br/><br />
					<input type="submit" name='submit' value="Submit" class='submit'/><br />
				</form>
			</div>
		</div>
	</div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</body>
</html>
