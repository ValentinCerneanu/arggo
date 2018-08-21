<?php
	session_start();
	include("head.php");
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "student"))
		header('Location: index.php');
	if(isset($_POST['submitTest']))
	{
		$cntExplode=explode(",", $_POST['submitTest']);
		$cnt=$cntExplode[0];
		$CorrectAns=0;
		$urlVerify = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/VariantAnswersListVC";
		$client = new SoapClient($urlVerify, ["login"=>$user, "password"=>$password]);
		for ($x = 1; $x <= $cnt; $x++) {
			if(isset($_POST[$x]))
		    {
		    	$Ans_ID=explode("," , $_POST[$x]);
				$Verify=$client->ReadMultiple(array("filter"=>array("Field"=>"Answer_ID", "Criteria"=>$Ans_ID[1]), "setSize"=>1))->ReadMultiple_Result;
				$Verify = $Verify->VariantAnswersListVC;
				$VerifyMultiple =$client->ReadMultiple(array("filter"=>array("Field"=>"Question_ID", "Criteria"=>$Ans_ID[0]), "setSize"=>0))->ReadMultiple_Result->VariantAnswersListVC;
				$Multiple=false;
				$cntM=0;
				foreach($VerifyMultiple as $verMult):
					if($verMult->Validation == "OK")
						$cntM++;
				endforeach;
				if($cntM>1)
					$Multiple=true;
				if($Multiple==false)
				{
					if($Verify->Validation=="OK")
						$CorrectAns++;
				}
				else
				{
					if(!isset($questions[$Ans_ID[0]]))
					{
						//calculam cate raspunsuri trebuie bifate aici
						$cntM=0;
						foreach($VerifyMultiple as $verMult):
							if($verMult->Validation == "OK")
								$cntM++;
						endforeach;
						$questions[$Ans_ID[0]]=$cntM;
						if($Verify->Validation=="OK")
							$questions[$Ans_ID[0]]--;
					}
					else
					{
						if($Verify->Validation=="OK")
							$questions[$Ans_ID[0]]--;
					}
				}
		    }	
		}
		if(isset($questions))
		foreach ($questions as $key ) {
			if($key==0)
				$CorrectAns++;
		}

	}
	$get = explode("," , $_GET["id"]); 

	$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/TestQuestionListVC";

	$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
	$Testare=$client->ReadMultiple(array("filter"=>array("Field"=>"Test_ID", "Criteria"=>$get[0]), "setSize"=>0))->ReadMultiple_Result;
	if(isset($Testare->TestQuestionListVC) AND is_array($Testare->TestQuestionListVC)) {
		$Testare = $Testare->TestQuestionListVC;
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
<body style="padding-bottom: 50px;">
	<div class="container-fluid">
    <div class="row">
	<div class="col-md-10 offset-md-1">
		<h3 style="text-align:center;">Test <?php echo $get[1]; ?></h3>
		<br>
		<ul class="list-group">	
		<form method="POST">
		<?php
		$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/VariantAnswersListVC";
		$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
		$cntAns=1;
		$cntQue=1;
		foreach($Testare as $Test):
			$Answers=$client->ReadMultiple(array("filter"=>array(array("Field"=>"Test_ID", "Criteria"=>$Test->Test_ID), array("Field"=>"Question_ID", "Criteria"=>$Test->Question_ID)), "setSize"=>0))->ReadMultiple_Result;
			if(isset($Answers->VariantAnswersListVC) AND is_array($Answers->VariantAnswersListVC)) {
				$Answers = $Answers->VariantAnswersListVC;
			}
			?>		  
		  	<li class="list-group-item list-group-item-primary">
				<?php echo $cntQue . '.' . " " . $Test->Question_Text . '<br>';
				if($Test->Question_Type == "Single"): ?>
					
					<?php
						foreach($Answers as $ans): ?>
						<input type="radio" name="<?php echo $cntAns;?>" value="<?php echo $ans->Question_ID . "," . $ans->Answer_ID; ?>" > <?php echo $ans->Answer_Text; ?> 
					<?php endforeach; ?>
					
				<?php endif;
				if($Test->Question_Type == "Multiple"): ?>
					
					<?php
						foreach($Answers as $ans):  ?>
						<input type="checkbox" name="<?php echo $cntAns;?>" value="<?php echo $ans->Question_ID . "," . $ans->Answer_ID; ?>" > <?php echo $ans->Answer_Text;  $cntAns++; ?> 
					<?php endforeach; ?>
					
					<?php 
					endif;
				?>
				<?php
				echo '<br>'; 
				?>
		  	</li>
		<?php 
			$cntAns++;
			$cntQue++; 
			endforeach;
			$cntAns--;
			$cntQue--;
		?>
		<br>
		<button name="submitTest" value="<?php echo $cntAns . "," . $cntQue; ?>" class="btn btn-primary">Insert</button>	
		</form>
	</ul>
		<?php
			echo "<br>";
			if(isset($CorrectAns))
			{
				$viewScore=$CorrectAns * 10;
				if($CorrectAns == $cntExplode[1])
					echo "<strong> <font size='10'> <p style='color:green';>" . $CorrectAns . "/" . $cntExplode[1] . "</p></font> </strong>";
				else
					echo "<strong> <font size='10'> <p style='color:red';>" . $CorrectAns . "/" . $cntExplode[1] . "</p></font></strong>";
				$urlScore="https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/StudentTestAnswerForSoapVC";
				$client = new SoapClient($urlScore, ["login"=>$user, "password"=>$password]);
				$NewScore = $client->Create(array("StudentTestAnswerForSoapVC"=>array("Student_ID"=>$_SESSION['student']->Student_ID, "Test_ID"=>$get[0], "Score"=>$viewScore)));
		
			}
		?>
	</div>
	</div>
	</div>
</body>
<?php include("footer.php");?>
</html>