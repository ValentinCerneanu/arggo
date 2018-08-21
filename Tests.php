<?php
	session_start();
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "student"))
		header('Location: index.php');
	include("head.php");
	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentTestListVC2";
	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
	$Tests=$client->ReadMultiple(array("filter"=>array("Field"=>"Student_ID", "Criteria"=>$_SESSION['student']->Student_ID), "setSize"=>0))->ReadMultiple_Result;
	if(isset($Tests->StudentTestListVC2) AND is_array($Tests->StudentTestListVC2)) {
		$Tests = $Tests->StudentTestListVC2;
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
<?php include("navbarStd.php"); ?>
<body>
	<div class="container-fluid">
    <div class="row">
	<div class="col-md-10 offset-md-1">
		<h3 style="text-align:center;">Tests</h3>
		<br>	
		<table class="table">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Category</th>
		      <th>Description</th>
		      <th>Due Date</th>
		      <th>Action</th>
		      <th>Score</th>
		    </tr>
		  </thead>
		  <tbody>
		  		<?php 
		  			$cnt=1;
		  			$urlInfo = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/TestListVC";
					$client = new SoapClient($urlInfo, ["login"=>$user, "password"=>$password]);
					foreach($Tests as $test): 
						$Info=$client->ReadMultiple(array("filter"=>array("Field"=>"Test_ID", "Criteria"=>$test->Test_ID), "setSize"=>0))->ReadMultiple_Result;
						$Info = $Info->TestListVC;
						if(isset($Info->TestListVC) AND is_array($Info->TestListVC)) 
						{
							$Info = $Info->TestListVC;
						}
				?>
						<tr>
				         	<th scope="row"><?php echo $cnt++; ?></th>
				          	<th><?php 
				          		if( $Info->Category == "C_x0023_")
				          			echo "C#";
				          		else
				          			echo $Info->Category;?></th>
				          	<th><?php echo$Info->Description;?></th>
				          	<th><?php echo$test->Due_Date;?></th>
				          	<th ><?php if(date("Y-m-d") <= $test->Due_Date): ?>
				         		<a href="Testare.php?id=<?php echo $Info->Test_ID . "," . $Info->Description ; ?>">Take Test</a>
				         		<?php 
				         			else: echo "Due date has expired";
				         		endif;
				         		?>
				         	</th>
				         	<th><?php
			         			$urlScore="https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentTestAnswerForSoapVC";
								$clientScore = new SoapClient($urlScore, ["login"=>$user, "password"=>$password]);
								$Scores=$clientScore->ReadMultiple(array("filter"=>array(array("Field"=>"Student_ID", "Criteria"=>$_SESSION['student']->Student_ID), array("Field"=>"Test_ID", "Criteria"=>$Info->Test_ID)), "setSize"=>0))->ReadMultiple_Result;
							
								if(isset($Scores->StudentTestAnswerForSoapVC))
								{
									if(is_array($Scores->StudentTestAnswerForSoapVC))
									{
										$Scores=$Scores->StudentTestAnswerForSoapVC;
										$maxScore=-1;
										foreach ($Scores as $score) 
											if($score->Score>$maxScore)
												$maxScore=$score->Score;
									}
									else 
										$maxScore=$Scores->StudentTestAnswerForSoapVC->Score;
									echo $maxScore;
								}	
			         			else
			         				echo "Unresolved Test";
			         		?> 
							</th>
			        	</tr>
		        	<?php endforeach; ?>
		  </tbody>
		</table>
	</div>
	</div>
	</div>
</body>
<?php include("footer.php");?>
</html>


