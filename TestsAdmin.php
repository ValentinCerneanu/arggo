<?php
	include("head.php");
	session_start();
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "admin"))
		header('Location: index.php');

	$error1form1 = $error2form1 = '';
	if(isset($_POST['insertTest']))
	{
	    $error1 = $error2 = $success1 = '';
		$description = $_POST['description'];
		$category = $_POST['category'];
		if(empty($_POST['description']))
			$error1form1 = "*You have to enter a description!";
		if(empty($_POST['category']))
			$error2form1 = "*You have to enter a category!";
		if($error1form1 == '' && $error2form1 == '')
		{
			$urlupd = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/TestListVC";
			$clientupd = new SoapClient($urlupd, ["login"=>$user, "password"=>$password]);
			$NewTest = $clientupd->Create(array("TestListVC"=>array("Description"=>$description, "Category"=>$category)));
		}
	}

	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/TestListVC";
	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);

	$Tests=$client->ReadMultiple(array("filter"=>array("Field"=>"", "Criteria"=>""), "setSize"=>0))->ReadMultiple_Result;

	if(isset($Tests->TestListVC) AND is_array($Tests->TestListVC)) {
		$Tests = $Tests->TestListVC;
	}
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
				<h3 style="text-align:center;">Tests</h3>
				<br>
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<table class="table">
				  <thead>
				    <tr>
				      <th>#</th>
				      <th>Description</th>
				      <th>Category</th>
				      <th>Action</th>
				    </tr>
				  </thead>
				  <tbody>
				  		<?php 
				  			$cnt=1;
							foreach($Tests as $test): ?>
							<tr>
					         	<th scope="row"><?php echo $cnt++; ?></th>
					          	<th><?php echo $test->Description;?></th>
					          	<th><?php 
						          		if( $test->Category == "C_x0023_")
						          			echo "C#";
						          		else
						          			echo $test->Category;?></th>
					          	<th ><a href="EditTest.php?id=<?php echo $test->Test_ID . "," . $test->Description?>">Edit</a></th>
				        	</tr>
				        	<?php endforeach; ?>
				  </tbody>
				</table>
				</form>
				</div>

				<div class="col-md-3">
					<h3 style="text-align:center;">Insert a new test</h3>
					<br>
					<form method="POST"  class="form form-login">

					<label for="name"><b>Insert description</b></label>
					<input class="form-control" type="text" name="description" placeholder="Test description">
					<?php 
						if($error1form1 != '')
							echo "<p style='color:red'>".$error1form1."</p>" 
					?>
					<label for="name"><b>Insert a category</b></label>
					<input class="form-control" type="text" name="category" placeholder="Test category">
					<?php 
						if($error2form1 != '')
							echo "<p style='color:red'>".$error2form1."</p>" 
					?>
					<br>
					<button name="insertTest" class="btn btn-primary">Insert</button>		
				</form>
				</div>
			</div>
		</div>
	</body>
	<?php include("footer.php");?>
</html>
