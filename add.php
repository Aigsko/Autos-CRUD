<?php 
require_once "pdo.php";
session_start();

// Check if we are logged in!
if (! isset($_SESSION['email']) ) {
	die('ACCESS DENIED');
}

// If the user requested logout go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}


//$make = htmlentities($_POST['make']);
//$year = htmlentities($_POST['year']);
//$mileage = htmlentities($_POST['mileage']);

if (isset($_POST['add'])) {
	unset($_SESSION['make']);
	unset($_SESSION['model']);
	unset($_SESSION['year']);
	unset($_SESSION['mileage']);

	if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
		$_SESSION['failure']  = "All fields are required";
		header('Location: add.php');
		return;
	}
	elseif (! is_numeric($_POST['year']) || (! is_numeric($_POST['mileage']))) {
		$_SESSION['failure'] = "Mileage and year must be numeric";
		header('Location: add.php');
		return;
	}
	else {
		$make = htmlentities($_POST['make']);
		$model = htmlentities($_POST['model']);
		$year = htmlentities($_POST['year']);
		$mileage = htmlentities($_POST['mileage']);

		$_SESSION['make'] = $make;
		$_SESSION['model'] = $model;
		$_SESSION['year'] = $year;
		$_SESSION['mileage'] = $mileage;

		$stmt = $pdo->prepare('INSERT INTO autos
        (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
    	$stmt->execute(array(
        ':mk' => $make,
        ':md' => $model,
        ':yr' => $year,
        ':mi' => $mileage)
    	);
    	$_SESSION['success'] = "Record added";  // green
    	header('Location:index.php');
    	return;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Aigars Skopāns Add to Database</title>
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">
    <link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

    <script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>
    <script
    src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
    integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
    crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<h1>Tracking Automobiles for <?= htmlentities($_SESSION['email']); ?></h1>

<?php
//Flash message
if (isset($_SESSION['failure']) ) {
    echo('<p style="color:red">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>

	  <form method="post" action="add.php">
		<p>Make:
		<input type="text" name="make" size="40"/></p>
		<p>Model:
		<input type="text" name="model" size="40"/></p>
		<p>Year:
		<input type="text" name="year" size="10"/></p>
		<p>Mileage:
		<input type="text" name="mileage" size="10"/></p>
		<input type="submit" name="add" value="Add">
		<input type="submit" name="cancel" value="Cancel">
	  </form>
	</div>
</body>
</html>
