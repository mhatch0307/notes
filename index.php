<!DOCTYPE html>
	<head>
		<title>Notes</title>
		<? include_once $_SERVER['DOCUMENT_ROOT']."/resources/components/header.php";
		   if(isset($_SESSION['userID']))
		   {
		   		header('location: home.php');
		   }
		?>
	</head>
	<body>
		<div id = "login">
			<form name = "login" action="/resources/utilities/login.php" method = "post">
				<div class = 'label'><label for = "username">Username: </label></div>
				<input type = "text" name = "username"/><br/>
				<div class = 'label'><label for = "password">Password: </label></div>
				<input type = "password" name = "password"/>
				<input type = 'submit' name = "login" id = "loginButton" value = "Log In"/>
			</form>
		</div>
	</body>
</html>