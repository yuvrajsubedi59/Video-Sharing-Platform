<?php

$con = mysqli_connect('localhost', 'root', '', 'isp_ys96');
$select = mysqli_select_db($con, "isp_ys96");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Video list</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
</head>

<body>
<h1>Enjoy our available videos for free</h1>
<h4>Simply click on the video name to play the video</h4>
<?php

$query = mysqli_query($con, "SELECT * FROM `videosname`");
while($row = mysqli_fetch_assoc($query))
{
	#$id = $row['id'];
	#$name = $row['name'];
	
	//echo "<a href=\'watch.php?id=$row['id']\'>$name</a><br />";
	?>
	<a href="watch.php?id=<?php echo $row['id']?>"><?php echo $row['name']?></a><br>
<?php
}
?>

</body>
</html>