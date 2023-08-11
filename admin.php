<?php include("includes/header.php") ?>

  
  <?php include("includes/nav.php") ?>



	<div class="jumbotron">
		<h1 class="text-center"><?php 

		if(logged_in()){

			echo "Logged in <a href='https://www.wolfwalkerjewelry.com/tahlequah_saturday_metalsmith_classes.php'>Click here!</a>";
		} else {


			redirect("index.php");
		}




		?></h1>
	</div>

<?php include("includes/footer.php") ?>