<?php

//require './vendor/autoload.php';

/****************helper functions ********************/


function clean($string) {


return htmlentities($string);


}



function redirect($location){


return header("Location: {$location}");

}


function set_message($message) {


	if(!empty($message)){


		$_SESSION['message'] = $message;

	}else {

		$message = "";

	}


}



function display_message(){


	if(isset($_SESSION['message'])) {


		echo $_SESSION['message'];

		unset($_SESSION['message']);

	}



}



function token_generator(){


$token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));

return $token;


}


function validation_errors($error_message) {

$error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<strong>Warning!</strong> $error_message
 </div>
DELIMITER;

return $error_message;
		




}



function email_exists($email) {

	$sql = "SELECT id FROM users WHERE email = '$email'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {


		return false;

	}



}



function username_exists($username) {

	$sql = "SELECT id FROM users WHERE username = '$username'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {


		return false;

	}



}


function send_email($email=null, $subject=null, $msg=null, $headers=null){


//	$mail = new PHPMailer();
//
//	$mail->isSMTP();
//	$mail->Host = Config::smtp.dreamhost.com;
//	$mail->Username = Config::wolf@wolfwalkerjewelry.com;
//	$mail->Password = Config::7evenClans;;
//	$mail->Port = Config::465;;
//	$mail->SMTPAuth = true;
//	$mail->SMTPSecure = 'ssl';
//	$mail->isHTML(true);
//	$mail->CharSet = 'UTF-8';
//
//	$mail->setFrom('wolf@wolfwalkerjewelry.com', 'Wolf');
//	$mail->addAddress($email);
//
//
//	$mail->Subject = $subject;
//	$mail->Body    = $msg;
//	$mail->AltBody = $msg;
//
//	if(!$mail->send()) {
//
//		echo 'Message could not be sent.';
//		echo 'Mailer Error: ' . $mail->ErrorInfo;
//
//	} else {
//		echo 'Message has been sent';
//	}
//


	return mail($email, $subject, $msg, $headers);


}



/****************Validation functions ********************/



function validate_user_registration(){

	$errors = [];

	$min = 3;
	$max = 20;



	if($_SERVER['REQUEST_METHOD'] == "POST") {


		$first_name 		= clean($_POST['first_name']);
+		$email 				= clean($_POST['email']);
		$cell_ph_number     = clean($_POST['cell_ph_number']);
		$username 		    = clean($_POST['username']);
		$password			= clean($_POST['password']);
		$confirm_password	= clean($_POST['confirm_password']);



		if(strlen($first_name) < $min) {

			$errors[] = "Your first name cannot be less than {$min} characters";

		}

		if(strlen($first_name) > $max) {

			$errors[] = "Your first name cannot be more than {$max} characters";

		}



		if(email_exists($email)){

			$errors[] = "Sorry that email has been registered";

		}




		if(strlen($email) < $min) {

			$errors[] = "Your email cannot be more than {$max} characters";

		}


		if(strlen($cell_ph_number) < $min) {

			$errors[] = "Your cell phone number cannot be less than {$min} characters";

		}


		if(strlen($cell_ph_number) > $max) {

			$errors[] = "Your cell phone number cannot be more than {$max} characters";

		}

		if(strlen($username) < $min) {

			$errors[] = "Your Username cannot be less than {$min} characters";

		}

		if(strlen($username) > $max) {

			$errors[] = "Your Username cannot be more than {$max} characters";

		}


		if(username_exists($username)){

			$errors[] = "Sorry that username is already is taken";

		}




		if($password !== $confirm_password) {

			$errors[] = "Your password fields do not match";

		}



		if(!empty($errors)) {

			foreach ($errors as $error) {

			echo validation_errors($error);

			
			}


		} else {


			if(register_user($first_name, $email, $cell_ph_number, $username, $password, $confirm_password)) {



				set_message("<p class='bg-success text-center'>Please check your email or spam folder for activation link</p>");

				redirect("index.php");


			} else {


				set_message("<p class='bg-danger text-center'>Sorry we could not register the user</p>");

				redirect("index.php");

			}



		}



	} // post request 



} // function 

/****************Register user functions ********************/

function register_user($first_name, $email, $cell_ph_number, $username, $password, $confirm_password){


	$first_name       = escape($first_name);
	$email            = escape($email);
	$cell_ph_number   = escape($cell_ph_number);
	$username         = escape($username);
	$password         = escape($password);
    $confirm_password = escape($confirm_password);



	if(email_exists($email)) {


		return false;


	} else if (username_exists($username)) {

		return false;

	} else {

		$password   = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));

		$validation_code = md5($username . microtime());

		$sql = "INSERT INTO users(first_name, email, cell_ph_number, username, password, confirm_password, validation_code, active)";
		$sql.= " VALUES('$first_name','$email','$cell_ph_number','$username','$password','$confirm_password','$validation_code, 0)";

		query('SET NAMES utf8');

		$result = query($sql);
		confirm($result);


		$subject = "Activate Account";
        $msg = "Please click the link below to activate your Account"
		<a href= "https://www.wolfwalkerjewelry.com/tahlequah/t_sat_bms/activate.php?email=$email&code=$validation_code">
		Tahlequah Saturday Beginner Metalsmith</a>;
		$headers = "From: noreply@wolfwalkerjewelry.com"

		$headers = "From: noreply@wolfwalkerjewelry.com";



		send_email($email, $subject, $msg, $headers);


		return true;

	}



} 


/****************Activate user functions ********************/


function activate_user() {


	if($_SERVER['REQUEST_METHOD'] == "GET") {


		if(isset($_GET['email'])) {


			$email = clean($_GET['email']);

			$validation_code = clean($_GET['code']);


			$sql = "SELECT id FROM users WHERE email = '".$_GET['email']."' AND validation_code = '".escape($_GET['code'])."' ";
			$result = query($sql);
			confirm($result);

			if(row_count($result) == 1) {

			$sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '".$email."' AND validation_code = '".escape($validation_code)."' ";
			$result2 = query($sql2);
			confirm($result2);

			set_message("<p class='bg-success'>Your account has been activated please login</p>");

			redirect("login.php");


		} else {

			set_message("<p class='bg-danger'>Sorry Your account could not be activated </p>");

			redirect("login.php");


			}




		} 


	}



} // function 

/****************Validate user login functions ********************/



function validate_user_login(){

	$errors = [];

	$min = 3;
	$max = 20;



	if($_SERVER['REQUEST_METHOD'] == "POST") {


		$email 		= clean($_POST['email']);
		$password	= clean($_POST['password']);
		$remember   = isset($_POST['remember']);




		if(empty($email)) {

			$errors[] = "Email field cannot be empty";

		}


		if(empty($password)) {

			$errors[] = "Password field cannot be empty";

		}



		if(!empty($errors)) {

				foreach ($errors as $error) {

				echo validation_errors($error);

				
				}


			} else {


				if(login_user($email, $password, $remember)) {


					redirect("admin.php");


				} else {


				echo validation_errors("Your credentials are not correct");		



				}



			}



	}


} // function 





/****************User login functions ********************/


	function login_user($email, $password, $remember) {


		$sql = "SELECT password, id FROM users WHERE email = '".escape($email)."' AND active = 1";

		$result = query($sql);

		if(row_count($result) == 1) {

			$row = fetch_array($result);

			$db_password = $row['password'];


			if(password_verify($password, $db_password)){

				if($remember == "on") {

					setcookie('email', $email, time() + 86400);

				}

				$_SESSION['email'] = $email;

				return true;

			}else {


				return false;
			}








			return true;

		} else {


			return false;



		}



	} // end of function



/****************logged in function ********************/



function logged_in(){

	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){


		return true;

	} else {


		return false;
	}




}	// functions




/****************Recover Password function ********************/



function recover_password() {


	if($_SERVER['REQUEST_METHOD'] == "POST") {

		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);


			if(email_exists($email)) {


			$validation_code = md5($email . microtime());


			setcookie('temp_access_code', $validation_code, time()+ 900);


			$sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
			$result = query($sql);



			$subject = "Please reset your password";
			$message = " <h2>Here is your password reset code, click the link below or paste in the browser</h2> <h1>{$validation_code}</h1>

			Paste your above copied password reset code, by clicking the following address link to reset your password:                         https://www.wolfwalkerjewelry.com/tahlequah/t_sat_bms/code.php?email=$email&code=$validation_code";

			$headers = "From: noreply@@wolfwalkerjewelry.com";





			send_email($email, $subject, $message, $headers);




			set_message("<p class='bg-success text-center'>Please check your email or spam folder for a password reset code</p>");

			redirect("index.php");


			} else {


				echo validation_errors("This emails does not exist");


			}



		} else {


			redirect("index.php");

		}




		// token checks

 
		if(isset($_POST['cancel_submit'])) {

			redirect("login.php");


		}



	} // post request





} // functions




/**************** Code  Validation ********************/


function validate_code () {


	if(isset($_COOKIE['temp_access_code'])) {

			if(!isset($_GET['email']) && !isset($_GET['code'])) {

				redirect("index.php");


			} else if (empty($_GET['email']) || empty($_GET['code'])) {

				redirect("index.php");


			} else {



				if(isset($_POST['code'])) {

					$email = clean($_GET['email']);

					$validation_code = clean($_POST['code']);

					$sql = "SELECT id FROM users WHERE validation_code = '".escape($validation_code)."' AND email = '".escape($email)."'";
					$result = query($sql);

					if(row_count($result) == 1) {

						setcookie('temp_access_code', $validation_code, time()+ 900);

						redirect("reset.php?email=$email&code=$validation_code");


					} else {



						echo validation_errors("Sorry wrong validation code");

					}
		




				}



			}








	} else {

		set_message("<p class='bg-danger text-center'>Sorry your validation cookie was expired</p>");

		redirect("recover.php");


	}







}



/**************** Password Reset Function ********************/


function password_reset() {

	if(isset($_COOKIE['temp_access_code'])) {


		if(isset($_GET['email']) && isset($_GET['code'])) {



			if(isset($_SESSION['token']) && isset($_POST['token'])) {


				if($_POST['token'] === $_SESSION['token']) {


					if($_POST['password']=== $_POST['confirm_password'])  { 


						$updated_password = md5($_POST['password']);


						$sql = "UPDATE users SET password = '".escape($updated_password)."', validation_code = 0, active=1 WHERE email = '".escape($_GET['email'])."'";
						query($sql);



						set_message("<p class='bg-success text-center'>You passwords has been updated, please login</p>");

						redirect("login.php");
						

						} else {

							echo validation_errors("Password fields don't match");


						}


				  }

	

			} 



		} 


	}else {


		set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		redirect("recover.php");




		}


}
?>







