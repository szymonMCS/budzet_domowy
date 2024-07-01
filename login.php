<?php
	session_start();
	
	$_SESSION['loggedIn'] = false;
	
	if((!isset($_POST['email'])) || (!isset($_POST['password']))){
		header('Location: logowanie.php');
		exit();
	}
	
	require_once "database.php";
	
	if(!isset($_SESSION['logged_id'])){
		
		$email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
		$password = $_POST['password'];
		
		$userquery = $db->prepare('SELECT id, username, password FROM users WHERE email= :email');
		$userquery->bindValue(':email', $email, PDO::PARAM_STR);
		$userquery->execute();
		
		$user = $userquery->fetch(PDO::FETCH_ASSOC);
		
		if($user && password_verify($password, $user['password'])){
			$_SESSION['logged_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['userpassword'] = $user['password'];
			$_SESSION['loggedIn'] = true;
			unset($_SESSION['bad_attempt']);
			header('Location: mainPage.php');
			exit();
		}else{
			$_SESSION['bad_attempt'] = true;
			$_SESSION['given_email'] = $email;
			header('Location: logowanie.php');
			exit();
		}
	}
	