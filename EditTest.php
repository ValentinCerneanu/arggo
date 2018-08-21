<?php
	session_start();
	include("head.php");
	$get = explode("," , $_GET["id"]);
	if(!(isset($_SESSION['user']) && $_SESSION['user'] == "admin"))
		header('Location: index.php');
	if(isset($_POST['create']))
	{
		$urlQuestions = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/TestQuestionListVC";
		$clientupd = new SoapClient($urlQuestions, ["login"=>$user, "password"=>$password]);
		$NewQuestions = $clientupd->Create(array("TestQuestionListVC"=>array("Test_ID"=>$get[0], "Question_Text"=>$_POST['question'], "Question_Type"=>$_POST['type'])));
		$urlAnswers = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/VariantAnswersListVC";
		$clientupd = new SoapClient($urlAnswers, ["login"=>$user, "password"=>$password]);
		if($_POST['type']=='Multiple')
		{
			for($i=0;$i<$_POST['cnt']; $i++)
			{
				$text='ans'.($i+1);
				$text1='validation'.($i+1);
				if($_POST[$text1]==$text)
					$NewAnswer = $clientupd->Create(array("VariantAnswersListVC"=>array("Test_ID"=>$get[0], "Question_ID"=>$NewQuestions->TestQuestionListVC->Question_ID, "Answer_Text"=>$_POST[$text1], "Validation"=>"OK")));
				else
					$NewAnswer = $clientupd->Create(array("VariantAnswersListVC"=>array("Test_ID"=>$get[0], "Question_ID"=>$NewQuestions->TestQuestionListVC->Question_ID, "Answer_Text"=>$_POST[$text1], "Validation"=>"NOT")));
			}
		}
		else
		{
			for($i=0;$i<$_POST['cnt']; $i++)
			{
				$text='ans'.($i+1);
				if($_POST['validation']==$text)
					$NewAnswer = $clientupd->Create(array("VariantAnswersListVC"=>array("Test_ID"=>$get[0], "Question_ID"=>$NewQuestions->TestQuestionListVC->Question_ID, "Answer_Text"=>$_POST['validation'], "Validation"=>"OK")));
				else
					$NewAnswer = $clientupd->Create(array("VariantAnswersListVC"=>array("Test_ID"=>$get[0], "Question_ID"=>$NewQuestions->TestQuestionListVC->Question_ID, "Answer_Text"=>$_POST['validation'], "Validation"=>"NOT")));
			}
		}
	}
 	
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
<?php include("navbarAdmin.php"); ?>
<body>
	<div class="container-fluid">
    <div class="row">
	<div class="col-md-5 offset-md-1">
		<h3 style="text-align:center;">Test <?php echo $get[1]; ?></h3>
		<br>
		<ul class="list-group">	
		<form method="POST">
		<?php
		$url = "https://internship.arggo.consulting:7047/DynamicsNAV100/WS/VC/Page/VariantAnswersListVC";
		$client = new SoapClient($url, ["login"=>$user, "password"=>$password]);
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
						<input type="radio" <?php if($ans->Validation=="OK"): ?> checked <?php endif?> value="<?php echo $ans->Question_ID . "," . $ans->Answer_ID; ?>" > <?php echo $ans->Answer_Text; ?> 
					<?php endforeach; ?>
					
				<?php endif;
				if($Test->Question_Type == "Multiple"): ?>
					
					<?php
						foreach($Answers as $ans):  ?>
						<input type="checkbox" <?php if($ans->Validation=="OK"): ?> checked <?php endif?> value="<?php echo $ans->Question_ID . "," . $ans->Answer_ID; ?>" > <?php echo $ans->Answer_Text; ?> 
					<?php endforeach; ?>
					
					<?php 
					endif;
				?>
				<?php
				echo '<br>'; 
				?>
		  	</li>
		<?php 
			$cntQue++; 
			endforeach;
			$cntQue--;
		?>	
		</form>
	</ul>
	</div>
	<div class="col-md-5">
		<h3 style="text-align:center;">Insert question</h3>
			<br>
			<form method="POST"  class="form form-login">
			<?php 
				if(!isset($_POST["next"])):
			?>
			<br>
			<label for="type"><b>Select question type</b></label>
			<select name="type">
			  <option value="Single">Single choice</option>
			  <option value="Multiple">Multiple choice </option>
			</select>
			<br>
			<br>
			<label for="cnt"><b>How many answers?</b></label>
			<select name="cnt">
			  <option value="2">2</option>
			  <option value="3">3</option>
			  <option value="4">4</option>
			</select>
			<br>
			<br>
			<button name="next" class="btn btn-primary">Next</button>		
			
			<?php
				else:
			?>
					<input name="type" type="hidden" value="<?php echo $_POST['type'];?>" >
					<input name="cnt" type="hidden" value="<?php echo $_POST['cnt'];?>" >
					<label for="question"><b>Text question</b></label>
			 		<input class="form-control" name="question" placeholder="Question">
			 	<?php
			 		if($_POST['type']=='Single'):
				 		for($i=0; $i<$_POST['cnt']; $i++):
				 		?>
				 		<label for="ans<?php echo $i+1;?>"><b>Answer no <?php echo $i+1;?></b></label>
				 		<input class="form-control" name="ans<?php echo $i+1;?>" placeholder="Answer">
				 		<input name="validation" type="radio" value="ans<?php echo $i+1;?>"> True Answer
				 		<br>

			 	<?php
			 		endfor;
			 		else:
				 		for($i=0; $i<$_POST['cnt']; $i++):
					 		?>
					 		<label for="ans<?php echo $i+1;?>"><b>Answer no <?php echo $i+1;?></b></label>
					 		<input class="form-control" name="ans<?php echo $i+1;?>" placeholder="Answer">
					 		<input name="validation<?php echo $i+1;?>" type="checkbox" value="ans<?php echo $i+1;?>"> True Answer
					 		<br>
					 	<?php
					 	endfor;	
				 	endif;	
			 	?>
			 	<br>			 		
			 	<button name="create" class="btn btn-primary">Create Question</button>	
			 	<?php
				 	endif;	
			 	?>
			</form>
	</div>
	</div>
	</div>
</body>
<?php include("footer.php");?>
</html>