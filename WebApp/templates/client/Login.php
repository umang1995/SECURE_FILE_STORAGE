<?php
/**
 * Created by PhpStorm.
 * User: Harsh Saxena
 * Date: 14-04-2016
 * Time: 12:23 PM
 */
session_start();
$_SESSION['timestamp'] = time();
include '../../scripts/timeout.php';
if(isset($_SESSION['user-name'])) {
    if($_SESSION['user']=="client")
    {
        header("location: Welcome.php");
    }

}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title> Client Login </title>
    <!-- Importing the CSS and the font for the website donot alter the section below -->
    <link rel="stylesheet" type="text/css" href="../../styles/prettify.css">
    <link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
    <!-- Importing ends here -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Plugins/captcha/jquery.realperson.css">
    <script type="text/javascript" src="../../Plugins/captcha/jquery.plugin.js"></script>
    <script type="text/javascript" src="../../Plugins/captcha/jquery.realperson.js"></script>
    <script>
        $(document).ready(function() {
            $('#real-person').realperson({chars: $.realperson.alphanumeric});
        });
	function Register(){
		window.location = "register.php";
	}
    </script>

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
    <!-- Top Nav Bar ends here -->

    <!-- Side bar ends here -->
    <!-- This is the section where you'll add the main content of the page -->
    <div id="main">
        <h1> Client Login </h1>
        <?php
        if(isset($_SESSION['user-name'])) {
            echo "Log out of all other sessions";
        }
        ?>
        <form  method="post">
            <input type="text" placeholder="Client Name" name="client" required> <br/>
            <input type="password" placeholder="Password" name="pass" required> <br/>
            <input type="text" placeholder="Captcha" name="captcha" id="real-person" required> <br/>
            <input type="submit" value="Sign In" class="submit-delete-button">
			<input type="button" value="Register" onclick = "Register()"  class="submit-delete-button"/>
        </form>
        <?php
        if(isset($_GET['logout'])){
            session_unset();
            session_destroy();
            header("Location: Login.php");
        }
        ?>
        <?php
        if($_POST) :
            function rpHash($value) {
                $hash = 5381;
                for($i = 0; $i < strlen($value); $i++) {
                    $hash = (($hash << 5) + $hash) + ord(substr($value, $i));
                }
                return $hash;
            }
            if (rpHash($_POST['captcha']) == $_POST['captchaHash']) {
                $dbhost = 'localhost';
                $dbuser = 'root';
                $dbpass = '';
                $dbname = 'sfs';
                $flag=0;
                $conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
                $pass=$_POST['pass'];
                $name=$_POST['client'];
                $sqll = "select name from user";
                $result_id = $conn->query($sqll);
                while ($row_id = mysqli_fetch_array($result_id))
                {
                    if($row_id['name']==$name)
                    {
                        $flag = 1;
                        $sql="select * from user where name='$name'";
                        $hashedsalt=hash('SHA512',$name,false);
                        $passhash=hash('SHA512',$pass,false);
                        $passhash=$passhash.$hashedsalt;
                        $result = $conn->query($sql);
                        $row = mysqli_fetch_array($result);
                        if ($passhash==$row['password']) {

                            $_SESSION['user-name'] = $row['id'];
                            $_SESSION['user'] = "client";
							$_SESSION['uname']=$name;			
                            header("Location: Welcome.php");
                        }
                        else {
                            echo "<p class='delete-message'>Invalid Password</p>";
                        }
                    }
                }
                if($flag == 0)
                {
                    echo "<p class='delete-message'>Invalid ID</p>";
                }
                $conn->close();
            }
            else {
                echo "<p class='delete-message'>Invalid Captcha</p>";
            }
        endif;
        ?>
    </div>
    <!-- The main content ends -->
</div>
<script src="../../scripts/timer.js"></script>
</body>
</html>