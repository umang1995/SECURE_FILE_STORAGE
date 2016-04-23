<?php
require('dbtophp.php');
$flag=0;
if(isset($_POST['submitBtn'])){
if(!empty($_POST['userName'])&&!empty($_POST['password'])){
			$userID = $_POST['userName'];
			$password = $_POST['password'];
			$hashedSalt = hash('SHA512',$userID,false);//hashing the salt value with SHA512
			$hashedpassword=hash('SHA512' ,$password,false);//hashing the password with SHA512
			$hashedpassword = $hashedpassword.$hashedSalt; //concatenating Password and Salt to get HASHSALT
			$query= "INSERT INTO user(`id`,`name`,`password`) VALUES (NULL,'$userID','$hashedpassword');";
			if($query_run = mysql_query($query)){
			$flag=1;
			}
}
}
?>
<html>
<head>
<style>
@import url(../../styles/prettify.css);
</style>
<script src="../../scripts/timer.js"></script>
</head>
<body>
<div id="container">
    <!-- This is the top nav bar -->
    <nav id="top-nav">
        <ul id="top-nav-list">
            <li class="top-nav-item" id="logo"> <img src="../../images/logo.png" alt="logo" id="logo-image"> </li> 
            <li class="top-nav-item" id="digital-clock"> <div id="clockDisplay" class="clockStyle"> </div> </li>
        </ul>
    </nav>
<center>
<div id="main">
<h1>Register Here</h1>
<form method="POST" action="register.php" name="myForm">
<label for="userName">UserName</label>
<input type="text" name="userName" placeholder="UserName" required/><br>
<label for="password">Password</label>
<input type="password" name = "password" placeholder="password" required/><br>
<input type="submit" name="submitBtn"/><br>
</form>
<?php
if($flag==1){
		mkdir("../../users/$userID");
		echo "<p>registration successful</p>";

		header("refresh:2; url=Login.php");
}
?>
</div>
</center>
</body>
</html>