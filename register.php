<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/styles.css">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/scripts.js"></script>

</head>
<body>

	 <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div><!--container-->
    </nav>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
    <?php include 'validate_user_registration';?>
    <?php include 'register_user';?>
    <?php include 'activate_user';?>

		</div>

    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="login.php">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="register.php" class="active" id="register-form">Register</a>
							</div>
						</div><!--row-->
						<hr>
					</div><!--panel-heading-->
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
                            <form id="register-form" form action="" method="post" role="form" >
									<div class="form-group">
										<input type="text" name="first_name" id="first_name" tabindex="1" class="form-control" placeholder="First Name" value="" required >
									</div>

									<div class="form-group">
										<input type="email" name="email" id="register_email" tabindex="2" class="form-control" placeholder="Email Address" value="" required >
									</div>


									<div class="form-group">
										<input type="text" name="cell_ph_number" id="cell_ph_number" tabindex="3" class="form-control" placeholder="Cell_Phone_Number" value="" required >
									</div>


									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="4" class="form-control" placeholder="Username" value="" required >
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="5" class="form-control" placeholder="Password" required>
									</div>
									<div class="form-group">
										<input type="password" name="confirm_password" id="confirm-password" tabindex="6" class="form-control" placeholder="Confirm Password" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="7" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
</body>
</html>