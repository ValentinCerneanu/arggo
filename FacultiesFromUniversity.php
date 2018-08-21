<?php
	include("head.php");
	session_start();
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "admin"))
		header('Location: index.php');
	if(!isset($_GET["id"]))
	{
		header("Location: Faculties.php");
	}

	$get = explode("," , $_GET["id"]); 
	if(!isset($get))
	{
		header("Location: Faculties.php");
	}

	$error1form1 = '';
	if(isset($_POST['insertfaculty']))
	{
	    $error1 = $error2 = $success1 = '';
		$name = $_POST['name'];
		$university = $get[0];
		if(empty($_POST['name']))
			$error1form1 = "*You have to enter the name of the faculty!";
		if($error1form1 == '')
		{
			$urlupd = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/FacultyListVC";
			$clientupd = new SoapClient($urlupd, ["login"=>$user, "password"=>$password]);

			$types = $clientupd->__getTypes();
			$NewFaculty = $clientupd->Create(array("FacultyListVC"=>array("Faculty_name"=>$name, "University_ID"=>$university)));
		}
	}

	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/FacultyListVC";
	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
	$Faculty=$client->Read(array("Faculty_ID"=>"F-001"));

	$Faculties=$client->ReadMultiple(array("filter"=>array("Field"=>"University_ID", "Criteria"=>$get[0]), "setSize"=>0))->ReadMultiple_Result;

	if(isset($Faculties->FacultyListVC) AND is_array($Faculties->FacultyListVC)) {
		$Faculties = $Faculties->FacultyListVC;
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
				<h3 style="text-align:center;">Faculties from <?php echo $get[1] ?></h3>
				<br>
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<table class="table">
				  <thead>
				    <tr>
				      <th>#</th>
				      <th>Name</th>
				      <th>Action</th>
				    </tr>
				  </thead>
				  <tbody>
				  		<?php 
				  			$cnt=1;
							foreach($Faculties as $fac): ?>
							<tr>
					         	<th scope="row"><?php echo $cnt++; ?></th>
					          	<th id="name<?php echo $fac->Faculty_name;?>"><?php echo $fac->Faculty_name;?></th>
					          	<th ><a href="Students.php?id=<?php echo $fac->Faculty_name . "," . $fac->Faculty_ID ?>">Show Students</a></th>
				        	</tr>
				        	<?php endforeach; ?>
				  </tbody>
				</table>
				</form>
				</div>

				<div class="col-md-3">
					<h3 style="text-align:center;">Insert a new faculty</h3>
					<br>
					<form method="POST"  class="form form-login">

					<label for="name"><b>Insert faculty name</b></label>
					<input class="form-control" type="text" name="name" placeholder="Faculty name">
					<?php 
						if($error1form1 != '')
							echo "<p style='color:red'>".$error1form1."</p>" 
					?>
					<br>
					<button name="insertfaculty" class="btn btn-primary">Insert</button>		
				</form>
				</div>
			</div>
		</div>
	</body>
	<?php include("footer.php");?>
</html>
