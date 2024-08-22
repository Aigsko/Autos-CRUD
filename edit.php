<?php
require_once "pdo.php";
session_start();

// Check if we are logged in!
if (! isset($_SESSION['email']) ) {
	die('ACCESS DENIED');
}

if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['autos_id']) ) {


    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
		$_SESSION['failure']  = "All fields are required";
		header("Location: edit.php?autos_id=".$_POST['autos_id']);
		return;
	}

    if (!is_numeric($_POST['year']) ) {
		$_SESSION['failure'] = "Year must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
 		return;
	}
    if (!is_numeric($_POST['mileage']) ) {
		$_SESSION['failure'] = "Mileage must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
 		return;
	}

    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

$ma = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>

<!DOCTYPE html>
<html>
<head>   
    <title>Aigars Skopāns Editing</title>
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
    <h1>Editing Automobile</h1>

<?php
    if ( isset($_SESSION["failure"]) ) {
    echo('<p style="color:red">'.$_SESSION["failure"]."</p>\n");
    unset($_SESSION["failure"]);
    }
?>

    <form method="post">
        <p>Make:
        <input type="text" name="make" size="40" value="<?= $ma ?>"></p>
        <p>Model:
        <input type="text" name="model" size="40" value="<?= $mo ?>"></p>
        <p>Year:
        <input type="text" name="year" size="10" value="<?= $y ?>"></p>
        <p>Mileage:
        <input type="text" name="mileage" size="10" value="<?= $mi ?>"></p>
        <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
        <p><input type="submit" value="Save"/>
        <input type="submit" name="cancel" value="Cancel">
    </form>
    
</div>
</body>
</html>
