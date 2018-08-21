<?php
	include("head.php");
	$success1 = $error1 = $error2 = $error3 = $error4 = '';
	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$pass = $_POST['password'];
		$found = false;
		$admin = false;  
		if(empty($_POST['username']))
			$error1 = '<font color="red">*You have to enter an username!</font><br>';
		if(empty($_POST['password']))
			$error2 = '<font color="red">*You have to enter a password!</font><br>';
		
		if($error1 == '' && $error2 == '')
		{
			//verificare credentiale
			//$hash =  password_hash('parola', PASSWORD_DEFAULT);
			if($username == "admin" && md5($pass) == "ebaeba023ae062227350824b9488dd99"){
				$admin = true;
			}
			else
			{
				$username = explode("." , $_POST['username']);
				$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentsListVC";
				$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
				$Students=$client->ReadMultiple(array("filter"=>array(array("Field"=>"Last_Name", "Criteria"=>$username[1]), array("Field"=>"First_Name", "Criteria"=>$username[0])), "setSize"=>1))->ReadMultiple_Result;
				if(isset($Students->StudentsListVC)) {
					$Students = $Students->StudentsListVC;
					if(password_verify($pass, $Students->Password))
					{
						$found=true;
					}
				}

				if($found == false)
					$error4 = '<font color="red">*The data you have introduced is wrong!</font><br>';
				else
				{
					$success1 = '<font color="green">*You have successfully logged in!</font><br>';
					$_SESSION['student'] = $variabila;
				}
			}
		}
		if($admin == true){
			session_start();
			$_SESSION['user'] = "admin";
			header('Location: Universities.php');
		}
		if($error4 == '' && $error3 == '' && $error2 == '' && $error1 == '' && !$admin==true){
			session_start();
			$_SESSION['user'] = "student";
			$_SESSION['student'] = $Students;
			header('Location: Tests.php');
		}
	}
?>