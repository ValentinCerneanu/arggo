<?php
	include("head.php");
	session_start();
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "admin"))
		header('Location: index.php');
	$error1form1 = $error2form1 = '';
	if(isset($_POST['insertuniversity']))
	{
	    $error1 = $error2 = $success1 = '';
		$name = $_POST['name'];
		$location = $_POST['location'];
		if(empty($_POST['name']))
			$error1form1 = "*You have to enter the name of the University!";
		if(empty($_POST['location']))
			$error2form1 = "*You have to enter the location of the University!";
		if($error1form1 == '' && $error2form1 == '')
		{
			$urlupd = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/UniversityList_VC";
			$clientupd = new SoapClient($urlupd, ["login"=>$user, "password"=>$password]);
			$NewUniv = $clientupd->Create(array("UniversityList_VC"=>array("University_Name"=>$name, "University_Location"=>$location)));
		}
	}

	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/UniversityList_VC";
	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
	$Universities=$client->ReadMultiple(array("filter"=>array("Field"=>"", "Criteria"=>""), "setSize"=>0))->ReadMultiple_Result->UniversityList_VC;

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
		<h3 style="text-align:center;">Universities</h3>
		<br>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<table class="table">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Name</th>
		      <th>Location</th>
		      <th>Action</th>
		    </tr>
		  </thead>
		  <tbody>
		  		<?php 
		  			$cnt=1;
					foreach($Universities as $univ): ?>
					<tr>
			         	<th scope="row"><?php echo $cnt++; ?></th>
			          	<th id="name<?php echo $univ->University_Name;?>"><?php echo $univ->University_Name;?></th>
			          	<th id="name<?php echo $univ->University_Location;?>"><?php echo $univ->University_Location;?></th>
			          	<th ><a href="FacultiesFromUniversity.php?id=<?php echo  $univ->University_ID . "," . $univ->University_Name ?>">Show Faculties</a></th>
		        	</tr>
		        	<?php endforeach; ?>
		  </tbody>
		</table>
		</form>
		</div>

		<div class="col-md-3">

			<h3 style="text-align:center;">Insert a new university</h3>
			<br>
			<form method="POST"  class="form form-login">

				<label for="name"><b>Insert university name</b></label>
				<input class="form-control" type="text" name="name" placeholder="University name">
				<?php 
					if($error1form1 != '')
						echo "<p style='color:red'>".$error1form1."</p>" 
				?>
				<label for="location"><b>Insert university location</b></label>
				<input class="form-control" type="text" name="location" placeholder="University location">
				<?php 
					if($error2form1 != '')
						echo "<p style='color:red'>".$error2form1."</p>" 
				?>
				<br>
				<button name="insertuniversity" class="btn btn-primary">Insert</button>		
			</form>
		</div>
	</div>
	</div>
</body>
<?php include("footer.php");?>
</html>
