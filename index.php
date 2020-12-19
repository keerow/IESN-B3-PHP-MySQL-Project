<?php
session_start();
include 'connection.php';

//Verifies login button pressed
if(isset($_POST["login"]))  
{  
	//Checks password and level of user in DB
	$query = "SELECT count(userid),level FROM MyDB.users WHERE userid = :username AND pwd= :password";  
	$statement = $connect->prepare($query);
	$statement->bindParam(':username',$_POST["username"]);
	$statement->bindParam(':password',$_POST["password"]);
	$statement->execute();
	$row =  ($statement->fetch());
	$auth = $row['count(userid)'];
	$isStudent = $row['level'];
	
	//Redirects student to exam page
	if($auth == 1 and $isStudent == 1)  {  
		$_SESSION["username"] = $_POST["username"];  
		header("location:login_success.php");
	}
	//Redirects professor to professor page
	elseif($auth == 1 and $isStudent == 0){
		$_SESSION["username"] = $_POST["username"];
		header("location:professor.php");
	}
	//Shows wrong credentials message
	else  
	{  
			$message = '<label>ID ou mot de passe erroné</label>';  
	}
}




?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css">
		<title>Login</title>
	</head>
	<body>
		<div class="login">
			<form method="post">
				<?php  
					echo '<label>'.$message.'</label>';  
					?>
				<input type="text" name="username" placeholder="User ID" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" name="login" value="Login" />
			</form>	
		</div>
	</body>
</html>