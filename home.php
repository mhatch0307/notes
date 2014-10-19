<!DOCTYPE html>
	<head>
		<? include_once $_SERVER['DOCUMENT_ROOT']."/resources/components/header.php";
		?>
		<title><?echo $_SESSION['fname']?>'s Notes</title>
		<script = src="/resources/js/home.js"></script>
	</head>
	<body>
		<div id = "noteSelector">
			<div id = "classes">
			</div>
			<div id = "topics">
			</div>
		</div>
		<div id = "noteView">
		</div>
	</body>
</html>