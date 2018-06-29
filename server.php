<?php
session_start();

require_once 'database.php';

$errors = [];
$success = [];

function getRandomString() {
	$length = 20;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if(isset($_POST['login'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['pwd']);
	$email = test_input($email);
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, "Invalid Email Format");
	}
	else if (mysqli_num_rows(mysqli_query($db, "SELECT * FROM students WHERE email='$email'")) == 0) {
		array_push($errors,"User does not exist. <a href=\"register.php\">Sign up</a>");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	if (count($errors)==0) {
		$res=mysqli_query($db, "SELECT saltstring,name,email,id FROM students WHERE email='$email'");
		$first = mysqli_fetch_assoc($res);
		$randstr = $first["saltstring"];
		$salt = sha1(md5($password)).$randstr;
		$password = md5($password.$salt);

		$res=mysqli_query($db, "SELECT id, email FROM students WHERE email='$email' AND password='$password'");
		if(mysqli_num_rows($res)>0) {
			$first = mysqli_fetch_assoc($res);
			$_SESSION['success'] = "You are now logged in.";
			$_SESSION['email'] = $first["email"];
			$_SESSION['student'] = $first["id"];
			$_SESSION['name'] = $first["name"];
			header('location: index.php');
			exit();
		}
		else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}
else if(isset($_POST['register'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['pwd']);
	$password_2 = mysqli_real_escape_string($db, $_POST['pwd_2']);
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = test_input($email);
	if (empty($name)) {
		array_push($errors, "Name is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, "Invalid Email Format");
	}
	else if (mysqli_num_rows(mysqli_query($db, "SELECT * FROM students WHERE email='$email'")) >= 1) {
		array_push($errors,"User already exists. <a href=\"login.php\">Sign in</a>");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	else if (empty($password_2)) {
		array_push($errors, "Confirm Password is required");
	}
	else if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}
	if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        //your site secret key
		$secret = '6LfnSWEUAAAAABMxV2yittt1W71pRHB_khC6UXEN';
        //get verify response data
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success) {
			// array_push($success,"Your contact request have submitted successfully.");
		}
		else {
			array_push($errors, "Robot verification failed, please try again.");
		}
	}
	else {
		array_push($errors,"Please click on the reCAPTCHA box.");
	}
	if (count($errors)==0) {
		$password = $password_1;
		$randstr = getRandomString();
		$salt = sha1(md5($password)).$randstr;
		$password = md5($password.$salt);

		$query = "INSERT INTO students (name, email, password, saltstring) VALUES('$name','$email', '$password','$randstr')";
		$res=mysqli_query($db, $query);

		if($res) {
			$query = "SELECT email,id,name FROM students where email='$email'";
			$first = mysqli_fetch_assoc(mysqli_query($db, $query));

			$_SESSION['success'] = "Successfully registered student and now logged in.";
			$_SESSION['email'] = $first["email"];
			$_SESSION['name'] = $first["name"];
			$_SESSION['user'] = $first["id"];
			header('location: index.php');
			exit();
		}
		else {
			array_push($errors,"Failed to register the student");
		}
	}
}

if(isset($_POST['employer-login'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['pwd']);
	$email = test_input($email);
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, "Invalid Email Format");
	}
	else if (mysqli_num_rows(mysqli_query($db, "SELECT * FROM employers WHERE email='$email'")) == 0) {
		array_push($errors,"User does not exist. <a href=\"employer-register.php\">Sign up</a>");
	}

	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	if (count($errors)==0) {
		$res=mysqli_query($db, "SELECT name,saltstring,email,id FROM employers WHERE email='$email'");
		$first = mysqli_fetch_assoc($res);
		$randstr = $first["saltstring"];
		$salt = sha1(md5($password)).$randstr;
		$password = md5($password.$salt);


		$res=mysqli_query($db, "SELECT id, email FROM employers WHERE email='$email' AND password='$password'");
		if(mysqli_num_rows($res)>0) {
			$_SESSION['success'] = "You are now logged in.";
			$_SESSION['email'] = $first["email"];
			$_SESSION['employer'] = $first["id"];
			$_SESSION['name'] = $first["name"];
			header('location: index.php');
			exit();
		}
		else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}
else if(isset($_POST['employer-register'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1= mysqli_real_escape_string($db, $_POST['pwd']);
	$password_2 = mysqli_real_escape_string($db, $_POST['pwd_2']);
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = test_input($email);
	if (empty($name)) {
		array_push($errors, "Name is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, "Invalid Email Format");
	}
	elseif (mysqli_num_rows(mysqli_query($db, "SELECT * FROM employers WHERE email='$email'")) >= 1) {
		array_push($errors,"User already exists. <a href=\"employer-login.php\">Sign in</a>");
	}

	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	else if (empty($password_2)) {
		array_push($errors, "Confirm Password is required");
	}
	else if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}
	if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        //your site secret key
		$secret = '6LfnSWEUAAAAABMxV2yittt1W71pRHB_khC6UXEN';
        //get verify response data
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success) {
			// array_push($success,"Your contact request have submitted successfully.");
		}
		else {
			array_push($errors, "Robot verification failed, please try again.");
		}
	}
	else {
		array_push($errors,"Please click on the reCAPTCHA box.");
	}
	if (count($errors)==0) {
		$password = $password_1;
		$randstr = getRandomString();
		$salt = sha1(md5($password)).$randstr;
		$password = md5($password.$salt);

		$query = "INSERT INTO employers (email,password,name,saltstring) VALUES('$email','$password','$name','$randstr')";
		error_log($query, 0);
		$res = mysqli_query($db, $query);

		if($res) {
			$query = "SELECT email,id,name FROM employers WHERE email='$email'";
			$first = mysqli_fetch_assoc(mysqli_query($db, $query));

			$_SESSION['success'] = "Successfully registered employer and now logged in.";
			$_SESSION['email'] = $first['email'];
			$_SESSION['employer'] = $first['id'];
			$_SESSION['name'] = $first["name"];
			header('location: index.php');
			exit();
		}
		else {
			array_push($errors,"Failed to register the employer");
		}
	}
}

if(isset($_GET['logout'])) {
	unset($_SESSION["success"]);
	unset($_SESSION["email"]);
	unset($_SESSION['student']);
	unset($_SESSION['employer']);
	session_destroy();
	header('location: index.php');
	exit();
}
?>