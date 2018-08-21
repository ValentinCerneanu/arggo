<?php
	include("head.php");
	session_start();
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "admin"))
		header('Location: index.php');
	$get = explode("," , $_GET["id"]); 

	$error1form1 = $error2form1 =$error3form1 ='';
	if(isset($_POST['insertstudent']))
	{
	    $error1 = $error2 = $success1 = '';
		$faculty = $get[1];
		if(empty($_POST['firstname']))
			$error1form1 = "*You have to enter the firstname!";
		if(empty($_POST['lastname']))
			$error2form1 = "*You have to enter the lastname!";
		if(empty($_POST['mail']))
			$error3form1 = "*You have to enter the mail!";
		if($error1form1 == '' AND $error2form1 == '' AND $error3form1 == '')
		{	
			$urlupd = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentsListVC";
			$clientupd = new SoapClient($urlupd, ["login"=>$user, "password"=>$password]);
			$NewStudent = $clientupd->Create(array("StudentsListVC"=>array("Last_Name"=>$_POST['lastname'], "First_Name"=>$_POST['firstname'], "Faculty_ID"=>$get[1], "Mail"=>$_POST['mail'])));
		}
	}

	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentsListVC";
	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);

	$Students=$client->ReadMultiple(array("filter"=>array("Field"=>"Faculty", "Criteria"=>$get[0]), "setSize"=>0))->ReadMultiple_Result;

	if(isset($Students->StudentsListVC) AND is_array($Students->StudentsListVC)) {
		$Students = $Students->StudentsListVC;
	}

	$fcs = $client->__getFunctions();
	$types = $client->__getTypes();


?>
<!doctype html>
<html lang="en">
	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="css/bootstrap.css">
	    <link rel="stylesheet" href="css/styles.css">

	    <title>Arggo</title>
  	</head>
    <?php include "navbarAdmin.php"; ?>
  	<body>
  		<div class="container-fluid">
	        <div class="row">
				<div class="col-md-7 offset-md-1">
					<h3 style="text-align:center;">Students from <?php echo $get[0] ?></h3>
				
					<table class="table">
					  <thead>
					    <tr>
					      <th>#</th>
					      <th>First Name</th>
					      <th>Last Name</th>
					      <th>Year of study</th>
					      <th>Mail</th>
					      <th>Action</th>
					    </tr>
					  </thead>
					  <tbody>
					  		<?php 
					  			//var_dump(json_decode('"' . "_x0031_" . '"'));
					  			$cnt=1;
								foreach($Students as $stud): ?>
								<tr>
									
						         	<td scope="row"><?php echo $cnt++; ?></td>
						          	<td id="name<?php echo $stud->Faculty_name;?>"><?php echo $stud->First_Name;?></td>
						          	<td id="name<?php echo $stud->Faculty_Location;?>"><?php echo $stud->Last_Name?></td>
						          	<td id="name<?php echo $stud->Faculty_name;?>"><?php echo $stud->Year_of_study[5];?></td>
						          	<td id="name<?php echo $stud->Faculty_Location;?>"><?php echo $stud->Mail?></td>
					        	</tr>
					        	<?php endforeach; ?>
					  </tbody>
					</table>
				</div>				
				<div class="col-md-3">
					<h3 style="text-align:center;">Insert a new student</h3>
					<br>
					<form method="POST"  class="form form-login">

					<label for="firstname"><b>Insert first name</b></label>
					<input class="form-control" type="text" name="firstname" placeholder="First name">
					<?php 
						if($error1form1 != '')
							echo "<p style='color:red'>".$error1form1."</p>" 
					?>
					<label for="lastname"><b>Insert last name</b></label>
					<input class="form-control" type="text" name="lastname" placeholder="Last name">
					<?php 
						if($error2form1 != '')
							echo "<p style='color:red'>".$error2form1."</p>" 
					?>
					<label for="Mail"><b>Insert mail</b></label>
					<input class="form-control" type="text" name="mail" placeholder="Mail">
					<?php 
						if($error3form1 != '')
							echo "<p style='color:red'>".$error3form1."</p>" 
					?>

					<br>
					<button name="insertstudent" class="btn btn-primary">Insert</button>		
				</form>
				</div>
			</div>
		</div>
	</body>
	<?php include("footer.php");?>
</html>
