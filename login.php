<?php // Do not put any HTML above this line
require_once "pdo.php";
session_start();

if ( isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['email']);
    $who = htmlentities($_POST['email']); 
    $pass = htmlentities($_POST['pass']);
    $_SESSION['email'] = $who;
    if ( strlen($who) < 1 || strlen($pass) < 1 ) {
        $_SESSION['error'] = "User name (email) and password are required";
        header('Location: login.php');
        return;
    } 

    elseif ( strpos($who,'@') ) {
      $check = hash('md5', $salt.$pass);
      if ( $check == $stored_hash ) {
        try {
          throw new Exception("Login success ".$who);
        }
        catch (Exception $ex) {
          error_log($ex->getMessage());
        }
        //$_SESSION["success"] = "Logged in.";
        
        // Redirect the browser to index.php
        header("Location: index.php");
        return;            
      } else {
          try {
            throw new Exception("Login fail ".$who." $check");
          }
          catch (Exception $e) {
            error_log($e->getMessage());
          }          
          $_SESSION["error"] = "Incorrect password.";
          header("Location: login.php");
          return;
      }     
    }    
    else {
        $_SESSION['error'] = "An email must contain the @ sign";
        header('Location: login.php');
        return;
    }    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aigars Skopāns Login Page</title>
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
    <h1>Please Log In</h1>

<?php
if ( isset($_SESSION["error"]) ) {
      echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
      unset($_SESSION["error"]);
}
?>
    <form method="POST" action="login.php">
      <label for="nam">User Name</label>
      <input type="text" name="email" id="nam" ><br/>
      <label for="id_1723">Password</label>
      <input type="pass" name="pass" id="id_1723"><br/>
      <input type="submit" value="Log In">
      <a href="logout.php">Cancel</a></p>
    </form>
    <p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the 
programming language used in this class (all lower case) 
followed by 123. -->
    </p>
  </div>
</body>
</html>
