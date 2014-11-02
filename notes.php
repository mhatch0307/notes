<!DOCTYPE html>
	<head>
		<? include_once $_SERVER['DOCUMENT_ROOT']."/resources/components/header.php";
		?>
		<title><?echo $_SESSION['fname']?>'s Notes</title>
	</head>
	<body>
		<div id = "noteHead">
			<h3><? echo $_SESSION['fname']?>'s Notes <!--  <br><span id = "signOut">Log Out</span></h3>-->
		</div>
		<div id = "noteSelector">
			<div id = "classes">
			</div>
			<div id = "topics">
			</div>
		</div>
		<div id = "noteView">
		</div>
		<div id = "editClasses" title = "Edit Classes">
		</div>
		<div id = "editTopic" title = "Edit Topic">
		</div>
	</body>
</html>