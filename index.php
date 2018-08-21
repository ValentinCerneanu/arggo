<?php require 'login.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">

    <title>Arggo SOAP</title>
  </head>
  <body>
    <br>
  	<div class="container">
  		<div class="row">
  			<div class="col-md-4">		
  			</div>
  			<div class="col-md-4">
          <div class="container">
            <br>
            <br>
            <br>
            <br>
            <p>Please fill in this form to sign in an account.</p>
            <hr>
            <br>
              <form method="POST"  class="form form-login">
              <label for="username"><b>Username</b></label>
    					<input class="form-control" type="text" name="username" placeholder="Enter username">
    					<?php 
    						if($error1 != '')
    							echo $error1;
    					?>
              <label for="password"><b>Password</b></label>
    					<input class="form-control" type="password" name="password" placeholder="Enter password">
    					<?php 
    						if($error2 != '')
    							echo $error2;
    					?>
    					<?php 
    						if($error3 != '')
    							echo $error3;
    						if($error4 != '')
    							echo $error4;
    						if($success1 != '')
    							echo $success1;
    					?>
              <p></p>
    					<button name="login" class="btn btn-primary">Login</button>
              <hr>
    				</form>
          </div>	
  			</div>
  			<div class="col-md-4">		
  			</div>
  		</div>
  	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/bootstrap.min.js"></script>
  </body>
  <?php include("footer.php");?>
</html>