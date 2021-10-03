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
  
  <!-- modal for online technical document-->
  <div class="modal fade" id="document">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1>Technical Document</h1>
        </div>
        <div class="modal-body">
          <h2>Architecture</h2>
          <p>
            Upon visiting our site, the first page that a user will see is a login page. If a user has already made 
            an account, they can login using their registered username and password. If they have not, they will have 
            to go to the sign up page and make one. Registering on our site stores the user’s chosen username in a 
            database along with a hashed version of their password, so that their password is not stolen. Registration 
            only requires choosing a username not in use and a password. <br>
            Once logged in, the user is sent to our homepage where they can view a list of currently uploaded videos, 
            choose to sign out of their account, or go to the upload page. On the upload page, the user may choose to 
            upload a new video from their computer, complete with a title, thumbnail and description for the video. 
            Uploading a new video stores the video and thumbnail in storage folders on the server and the other related 
            information in a separate database. This includes the name of the video, the storage location of the video 
            and thumbnail, and the user provided description for the video. <br>
            Clicking on one of the listed video previews on the homepage will open the video in the user’s web browser 
            using the html video tag so that they can watch it. It will also display the video’s title, description, and 
            upload date underneath the video, along with who uploaded it and a list of other videos for the user to watch.
          </p>
          <h2>Business Logic</h2>
          <p>
            With our project providing the same surface level service as the already well established Youtube, 
            our website would have to differentiate itself in a way that would be able to compete. Many content 
            creators on Youtube have a lot of gripes with their service, primarily with how video recommendations work and issues with 
            monetization of their videos, so by addressing the problems that they have on Youtube and guaranteeing 
            they won’t have the same issues on our site, many content creators may choose to migrate to our platform. <br>
            To do this, we plan to come up with a different way from Youtube to recommend videos to users on the 
            homepage, and also address how ads will be handled. We also plan to come up with some way to promote 
            individual content creators who aren’t already basically celebrities, to put the focus of our site on the 
            content creators that help to make our website what it is.
          </p>
          <h2>Database Design</h2>
          <p>
            We have a database setup with two different tables. The first table is used for user accounts. It stores the usernames
            and passwords for every account that is made on our website. The passwords are hashed before storage for an extra 
            layer of security for the accounts. The second table is used for uploaded video information. It stores the title, 
            description, and file path for every video uploaded along with the filename of the video and thumbnail (if one 
            was provided) and the username of who uploaded the video.
          </p>
          <h2>Member Contributions</h2>
          <p>
            Our original intent was to have the website be accessible through Amazon Web Services, so I (Kyle) had made an 
            AWS account so that we may work on doing that. I set up an Elastic Beanstalk (EB) instance for us to host our 
            website on and setup an RDS database for us to use as well. The login and homepage were written by Yuvraj and 
            uploaded to the EB instance, but proved to have some sort of incompatibility issue with EB and how we were 
            connecting to the database, so we uploaded it to Pausch instead and got it working there. The database for video 
            information is still hosted on AWS, however the videos themselves are not. I set up an AWS S3 bucket so that we 
            could host the videos on AWS, but we ended up not using it after moving the website to Pausch since we would have 
            had to install the AWS SDK. The page for uploading new videos and viewing them was started by me, but Yuvraj ended 
            up finishing it. I added the technical document and the online help to website, along with the popups for viewing them. 
            Yuvraj and I both worked on the css for the website, making any changes we each liked.
          </p>
        </div>
        <div class="modal-footer">
          <a href="ISP Project Powerpoint.ppt" target="_blank">View Project Powerpoint</a>
          <a href="ISP Project Report.pdf" target="_blank">View Project Report PDF</a>
          <input type="button" class="btn btn-primary" data-dismiss="modal" value="Close">
        </div>
      </div>
    </div>
  </div>  

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
            To register an account on our website, you must first click on the Register button to go to the registration page. 
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