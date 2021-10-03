<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$con = mysqli_connect('localhost', 'root', '', 'isp_ys96');
$select = mysqli_select_db($con, "isp_ys96");
if(isset($_POST['submit']))
{
    $thumbname=$_FILES['file2']['name'];
    $thumbtemp=$_FILES['file2']['tmp_name'];
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $uploader=htmlspecialchars($_SESSION["username"]);
    $des=$_POST['des'];
    $title=$_POST['title'];

	
    move_uploaded_file($temp,"uploaded/".$name);
    move_uploaded_file($thumbtemp,"thumbnails/".$thumbname);
	$des=$_POST['des'];
	$url = "http://localhost/isp_project/uploaded/$name";
    $query = mysqli_query($con, "INSERT INTO `videosname` (`name`,`url`,`des`,`thumbnail`,`uploader`,`title`)VALUE ('$name','$url','$des','$thumbname','$uploader','$title')");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/style.css">
	<script src="https://kit.fontawesome.com/ec0db17b08.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
</head>
<body style="background:#0fa">
<section>
<a href="byuser.php?un=<?php echo  htmlspecialchars($_SESSION['username']);?>" class="user-link">
<div class="user-intro"><i class="far fa-user fa-2x"></i>
<b><?php echo htmlspecialchars($_SESSION["username"]);?></b><br>
</a>
<a href="logout.php" class="btn btn-danger" style="left:50%"> Log out</a>
</div>
<div class='up-ll'><a href="welcome.php" class="btn btn-success">Main Page <i class="fas fa-home"></i></a></div>
	<div class='title'><h1 style='margin-left:20%;'>VSP</h1><p>Video Sharing Platform</p></div>
</section>
<section class="sect-2">
    <hr>
    <div class="wrapper">
    <form action="upload.php" method="POST" enctype="multipart/form-data" class="form" name="form">
    <h2 style="color:white; font-size:30px; font-family:'Comic Sans MS', cursive, sans-serif;"> Upload your videos here: </h2>
    <label for="file" style="color:white">Video:</label>
    <input type="file"  class="btn btn-success" name="file" accept="video/*"/ required>
    <br>
    <label for="file2" style="color:white">Thumbnail:</label>
    <input type="file" name="file2" class="btn btn-success" id="thumbnail" accept="image/*">
    <br>
    <label for="title" style="color:white">Title for video:</label>
    <input type="text" name="title" id="title" required>
    <br>
    <label for="des" style="color:white">Description:</label>
    <input type="text" name="des" id="des" required>
    <br>
    <input type="submit" name="submit" value="Upload!" class="btn btn-success"/>
    </form>
    </div>
   
</section>
<?php

	if(isset($_POST['submit']))
	{
		echo "<br />".$name." has been uploaded";
    }

    ?>
    
</body>
</html