<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $username_err = $password_err = "";
//$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $db = "SELECT id, username, password FROM Users WHERE username = ?";
        
        if($statement = $mysqli->prepare($db)){
            // Bind variables to the prepared statement as parameters
            $statement->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Store result
                $statement->store_result();
                
                // Check if username exists, if yes then verify password
                if($statement->num_rows == 1){                    
                    // Bind result variables
                    $statement->bind_result($id, $username, $hashed_password);
                    if($statement->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $statement->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">-->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">

</head>
<body>  

  <!-- modal for online help-->
  <div class="modal fade" id="help">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1>Help Me!!!</h1>
        </div>
        <div class="modal-body">
          <h2>Registering</h2>
          <p>
            To register an account on this website, you must first click on the Register button to go to the registration page. 
            From there, simply type in your desired username and password for your account. If an account already exists with your
            chosen username, you will need to choose a different username. Passwords must be at least 6 letters long to register an 
            account.
          </p>
          <h2>Logging In</h2>
          <p>
            Simply type in the username and password associated with your account to login. A successful Login will take you to
            the website's homepage, where you may view uploaded videos or upload your own. If the typed username is not found, please
            check that it was not mispelled and try again. If you do not already have an account, you will have to use the registration
            page to make one before you can login.
          </p>
          <h2>Uploading New Videos</h2>
          <p>
            The page for uploading your own videos can be accessed by clicking the upload button in the top left corner of the 
            homepage. From here, you can choose a video and a thumbnail from your computer to upload and add a title and 
            description for the video. Only the thumbnail is not required. A message will be displayed on the page if your video
            upload was successful.
          </p>
          <h2>Watching</h2>
          <p>
            Videos can be accessed from the homepage. Simply click on a thumbnail for a video to go to the video's page. Here, you 
            will see the video itself with the video's information and other related videos you may want to watch displayed at the 
            bottom of the page.
          </p>
          <h2>Logging Out</h2>
          <p>
            You can log out of your account from any page (other that the login and register pages) by clicking the red log out 
            button in the top right corner of the website. Logging out will take you back to the login page.
          </p>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-primary" data-dismiss="modal" value="Thank You!!!">
        </div>
      </div>
    </div>
  </div>

  <!-- needed for bootstrap modals -->
  <script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  <!-- login box -->
  <div class="wrapper" width="500">
    <h2 style = "color:white; font-weight: bold;" >Login to our Video Page</h2>
    <p style = "color:white; font-weight: bold;">Please fill in your username and password to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        <label style = "color:white">Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
        <span style = "color:#04FC0E" class="help-block"><?php echo $username_err; ?></span>
      </div>    
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label style = "color:white">Password</label>
        <input type="password" name="password" class="form-control">
        <span style = "color:#04FC0E" class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Login">
      </div>
      <p id="dont">Don't have an account? <a href="register.php" class="btn btn-primary" role="button" aria-pressed="true">Register</a></p>
    </form>
    <!-- modal buttons -->
    <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#document" value="Learn More">
    <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#help" value="I Need Help!">
  </div>

</body>
</html>