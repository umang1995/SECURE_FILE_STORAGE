<?php
session_start();
include '../../scripts/timeout.php';
require('dbtophp.php');
$userName=$_SESSION['uname'];			
if(isset($_FILES['file'])&&isset($_POST['hashme'])&&isset($_POST['latitude'])&&isset($_POST['longitude']))
{
	$name = $_FILES['file']['name']; // name of file uploaded
	$location = "../../users/$userName/";	//location that we want to save
	$fileLoc = $location.$name;
	$tmp_name = $_FILES['file']['tmp_name']; // temp file name when loading on server
    $type = $_FILES['file']['type']; // type of file
	$extension = strtolower(substr($name,strpos($name,'.')+1));
	$size = $_FILES['file']['size']; //size of file
	$max_size = 2097152; // max file size
	$file_extension = array('jpg','jpeg','docx','doc','pdf','txt');
	$file_type = array('application/pdf','image/jpeg','application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	$password =$_POST['hashme'];
	$results='';
	$algo='SHA512';
	$latitude = $_POST['latitude'];
	$longitude= $_POST['longitude'];
	//if all the values of the form are non empty then work;&&!empty($latitude)&&!empty($longitude)
	if(!empty($name)&&!empty($password))
	{
		
		$flag=0;//flag to see if the uploaded file is of correct extension
		foreach($file_extension as $ext){
	    if($ext==$extension){
			$flag=1;
			break;
		}
		else{
			$flag=0;
		}
	    }
		//if extension is correct then process
		if($flag==1){
			if (move_uploaded_file($tmp_name , $location.$name)){
			//file has been uploaded password hashing is to be done and stored in database
			$results = "File ".$name." has been uploaded";
			$raw_output=false;
			$salt = rand();//random salt (random number)
			$hashedSalt = hash('SHA512',$salt,false);//hashing the salt value with SHA512
			$hashedpassword=hash($algo ,$password,$raw_output);//hashing the password with SHA512
			$hashedpassword = $hashedpassword.$hashedSalt; //concatenating Password and Salt to get HASHSALT
			
			$key = hash('ripemd128',$password,false);//generating a 128 bit key from the password
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);//initialization vector used to make the encrryption more stronger
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);//generate the iv by the method
			$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$latitude,MCRYPT_MODE_CBC, $iv); //encrypting latitude with the AES algorithm
			$ciphertextLon = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$longitude,MCRYPT_MODE_CBC, $iv); //encrypting longitude with the AES algorithm
			$query = "INSERT INTO `password`(`ID`,`user_id`,`location`,`SS`,`password`,`longitude`,`latitude`,`IV`) VALUES (NULL,'".$userName."','".$location.$name."','".$hashedSalt."','".$hashedpassword."','".$ciphertext."','".$ciphertextLon."','".$iv."');";
			$query_run=mysql_query($query);//processing the query 
			//encrypting the file uploaded.
			$handle = fopen($fileLoc,'r+') or die(@"FILE NOT OPENED");//opening the file in read and write mode without truncating anthing
			$PlaintextFile=fread($handle,filesize($fileLoc));//the file to be encypted is read in the plaintextfile var
			$ciphertextFile = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$PlaintextFile,MCRYPT_MODE_CBC, $iv); //encrypting the data read from the file					
			fclose($handle);//close the handle used for reading the contents of the file
			$handle = fopen($fileLoc,'w') or die(@"FILE NOT OPENED");//open the handle for writing the encrypted data to the file
			fwrite($handle,$ciphertextFile);//write the data to the file
			fclose($handle);//close the handle used for writing
			$files = file($fileLoc);//open the encrypted file using file function 
			$opentext='';//a var which will carry the encrypted data from the file
			//loop used to make the encrypted data byte by byte that will be decrypted later
			foreach($files as $message){
				$opentext = $opentext.$message;
			}
			echo "<br>";
			echo $textFile = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$opentext,MCRYPT_MODE_CBC, $iv); //decrypting the message formed after the loop
			echo "<br>";
			}
			else{
		      $results = 'File not uploaded';
		    }
		}
		else{
			$results = 'choose correct file type';
		}
	}
	else if(empty($name)){
		$results = 'please choose a file';
	}
	else if(empty($password)){
		$results = 'enter password';
	}
	else if(empty($latitude)||empty($longitude)){
		$results='File upload failed, connect to internet and try again.!';
	}
	else{
		$results = 'Choose File and Enter Password';
	}
}
else{
	$results = 'select a file and submit';
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title> Welcome </title>
    <!-- Importing the CSS and the font for the website donot alter the section below -->
    <link rel="stylesheet" type="text/css" href="../../styles/prettify.css">
    <link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
    <!-- Importing ends here -->

    <link rel="stylesheet" type="text/css" href="../../styles/admin.css">
    <script src="../../scripts/js-admin-add-project.js"> </script>
</head>

<body>
<div id="container">
    <!-- This is the top nav bar donot make changes here -->
    <nav id="top-nav">
        <ul id="top-nav-list">
            <li class="top-nav-item" id="logo"> <img src="../../images/logo.png" alt="logo" id="logo-image"> </li> 
            <li class="top-nav-item" id="digital-clock"> <div id="clockDisplay" class="clockStyle"> </div> </li>
            <li class="top-nav-item" id="logout-button"> <a id="logout-link" href="Login.php?logout=1"> Logout </a> </li>
        </ul>
    </nav>
    <!-- Top Nav Bar ends here -->

    <!-- Side nav bar is below make changes  -->
    <aside id="side-nav">
        <ul id="side-nav-list">
            <li id="home" class="side-nav-items active"> <a href="Welcome.php" class="nav-link active-link"> Home </a> </li>
            <li id="upload" class="side-nav-items"> <a href="upload.php" class="nav-link"> Upload File </a> </li>
            <li id="download" class="side-nav-items"> <a href="download.php" class="nav-link"> Download File </a> </li>

        </ul>
    </aside>
    <!-- Side bar ends here -->

    <!-- This is the section where you'll add the main content of the page -->
    <div id="main">
        <?php
        if(isset($_SESSION['user-name']) && $_SESSION['user']=="client"){
            ?>
			<h1>UPLOAD FILE</h1>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
Select File:
<input type="file" name="file"><br><br>
Password:
<input type="password" name="hashme"><br><br>
<p id="results" name="results" style="color:red; font-size:20px; font-weight:bold;"><?php echo $results?></p>
<input type="hidden" id="latitude" name="latitude"/>
<input type="hidden" id="longitude" name="longitude"/>
<input type="submit" name="submitBtn"/>
</form>
            <?php
        }
        else
        {
            echo "<p class='delete-message'> You must be logged in to see this page </p>";
        }
        ?>
    </div>
    <!-- The main content ends -->
</div>
</body>
<script src="../../scripts/timer.js"></script>
</html>
