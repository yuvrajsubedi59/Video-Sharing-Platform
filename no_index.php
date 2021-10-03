<?php

$con = mysqli_connect('localhost', 'root', '', 'isp_ys96');
$select = mysqli_select_db($con, 'isp_ys96');

if(isset($_POST['submit']))
{
	$name = $_FILES['file']['name'];
	$temp = $_FILES['file']['tmp_name'];
	
	move_uploaded_file($temp,"uploaded/".$name);
	$des=$_POST['des'];
	$url = "http://localhost/PHP1/uploaded/$name";
	$query = mysqli_query($con, "INSERT INTO `videosname` (`name`,`url`,`des`)VALUE ('$name','$url','$des')");
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Video Upload page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
</head>

<body>

<a href="videos.php">Videos</a>
<form action="index.php" method="POST" enctype="multipart/form-data">
	<input type="file" name="file" accept="video/*"/>
	<input type="text" placeholder="Description:" name="des">
    <input type="submit" name="submit" value="Upload!" />
</form>

<?php

if(isset($_POST['submit']))
{
	echo "<br />".$name." has been uploaded";
}

?>

</body>
</html>