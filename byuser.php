<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'isp_ys96');
$select = mysqli_select_db($con, 'isp_ys96');

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

<?php

if(isset($_GET['un']))
{
    $uploader = $_GET['un'];
    $who="";
	$query = mysqli_query($con,"SELECT * FROM `videosname` WHERE uploader='$uploader'");
	switch ($uploader) {
        case '':
            header("location: welcome.php");
        break;
        case $_SESSION['username']:
            $who="you";
        break;
        default:
            $who=$uploader;
            break;
    }
?>
	<section class="sect-2">
    <hr>
    <h3 style="margin-left:40%;">Videos uploaded by <?php echo $who?>.</h3>
	<?php
}
while($row = mysqli_fetch_assoc($query))
{
	#$id = $row['id'];
	#$name = $row['name'];
	
	//echo "<a href=\'watch.php?id=$row['id']\'>$name</a><br />";
	?>
<!--	<a href="watch.php?id=<?php echo $row['id']?>"><?php #echo $row['name']?></a><br>-->
<a href="watch.php?id=<?php echo $row['id']?>">
<div class="card" style="width: 14rem;">
  <img src="thumbnails/<?php echo $row['thumbnail']?>" class="card-img-top" alt="<?php echo $row['name']?>" onerror="this.src='thumbnails/nothumb.png'">
  <div class="card-body">
	  <h3 class="card-title"><?php echo $row['title']?></h3>
    <p class="card-text"><?php echo $row['des']?></p>
  </div>
</div>
</a>
<?php
}
?>
</section>
</body>
</html>