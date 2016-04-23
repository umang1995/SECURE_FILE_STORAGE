<?php
session_start();
include '../../scripts/timeout.php';
$userName=$_SESSION['uname'];		
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
            $directory ="../../users/$userName/";	//location that we want to save
			if($handle = opendir($directory))//open directory of the uploaded files
				{
					echo 'Looking inside \''.$directory.'\'<br/>';
					while($file = readdir($handle))//reading all file names in the folder uploadedFiles
						{
							if($file!='.'&&$file!='..')
		echo '<a href = "$directory.'/'.$file.'">'.$file.'</a><br>';	
	}
}
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
