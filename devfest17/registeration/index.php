<?php
/* Registeration page for GDG Cochin DevFest 2017 */
/* Design inspired from Google Solve For India Regsiteration Page */
session_start();
$con = mysqli_connect("localhost","root","","devfest17");

// Check connection
if (mysqli_connect_errno())
{
	die("Failed to connect to MySQL: " . mysqli_connect_error());
}
date_default_timezone_set("Asia/Kolkata");

	/* VALUES */
	$mail = "";
	$firstname = "";
	$lastname = "";
	$agree = 0;
	$gender = "m";
	$company = "";
	$package = "";
	$webappurl = "";
	$job = 0;
	$gitorstack = "";
	$afternoon = "an";
	$aim = "";

$stage = 0;
$error ="";

if(filter_var("http://localhost.photography/GDGCochin.githubഅ.io/devfest17/register/", FILTER_VALIDATE_URL)){
	die("S");
}
if(isset($_POST['mail']) && !isset($_SESSION['mail']))
{

	$_mail = mysqli_real_escape_string($con,$_POST['mail']);
	if(filter_var($_mail, FILTER_VALIDATE_EMAIL)){
		$_SESSION['mail'] = $_mail;
 
		$query = mysqli_query($con,"SELECT * FROM `registeration` WHERE `email` = '".$_mail."'");
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);
			$stage = 1;
			if($row['status'] > 0){
				$stage = 2;
			}
			$mail = $_mail;
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];

			$agree = 1;
			$gender = $row['gender'];
			$company = $row['company'];
			$package = $row['package'];
			$webappurl = $row['webapp'];
			$job = $row['job'];;
			$gitorstack = $row['gitorstack'];
			$afternoon = $row['afternoon'];
			$aim = $row['aim'];

		}
		else{
			if(mysqli_query($con,"INSERT INTO `registeration` (`email`,`time`) VALUES ('".$_mail."','".date("Y-m-d H:i:s")."')")){
				$stage = 1;
				$mail = $_mail;
				$_SESSION['mail'] = $mail;
			}
			else{
			    die("DATABASE Error: " . mysqli_error($con));
			}
		}
	}
	else{
		$stage = 0;
		$error .= "* Not a valid mail<br/>";
	}
}
elseif(isset($_POST['details']) && isset($_SESSION['mail'])){

	if(isset($_POST['firstname'],$_POST['lastname'],$_POST['gender'],$_POST['company'],$_POST['project'],$_POST['webappurl'],$_POST['job'],$_POST['gitorstack'],$_POST['afternoon'],$_POST['aim'])){

		$stage = 1;

		$mail = $_SESSION['mail'];

		if(!isset($_POST['agree'])){
			$error .= "* Check the acknowledgement box<br/>";
		}
		$agree = 1;
		$firstname = mysqli_real_escape_string($con,$_POST['firstname']);
		$lastname = mysqli_real_escape_string($con,$_POST['lastname']);
		if(strlen($firstname) > 35 || strlen($lastname) > 35){
			$error .= "* Maximum First & Second names length is 35<br/>";
		}
		$gender = mysqli_real_escape_string($con,$_POST['gender']);
		if(!in_array($gender, ['m','f','o'])){
			$error .= "* Invalid Entry for Gender<br/>";
		}
		$company = mysqli_real_escape_string($con,trim($_POST['company']));
		if(strlen($company) <2){
			$error .= "* Not a valid company name<br/>";
		}
		$package = mysqli_real_escape_string($con,trim($_POST['project']));
		if(strlen($package)<6){
			$error .= "* Your solution's package ID / playstore link / github project repo is not valid<br/>";
		}
		$webappurl = mysqli_real_escape_string($con,trim($_POST['webappurl']));
		$job = mysqli_real_escape_string($con,intval($_POST['job']));
		if($job < 0 || $job > 2){
			$error .= "* Not a valid entry for day to day job feild<br/>";
		}
		$gitorstack= mysqli_real_escape_string($con,$_POST['gitorstack']);
		if(strlen($gitorstack)<11){
			$error .= "* Not a valid github or stackeoverflow profile<br/>";
		}
		$afternoon = mysqli_real_escape_string($con,$_POST['afternoon']);
		if($afternoon != "an" && $afternoon != "ml" ){
			$error .= "* Not a valid entry for afternoon session<br/>";
		}
		$aim = mysqli_real_escape_string($con,$_POST['aim']);
		if(strlen(trim($aim)) < 10 ){
			$error .= "* Please let us know what you would like to learn from this event ( Minimum 10 Characters)<br/>";
		}

		if($error == ""){
			if(mysqli_query($con,"UPDATE `registeration` SET `firstname`= '$firstname' , `lastname` = '$lastname' ,`gender`='$gender',`company` = '$company', `package`= '$package',`webapp`= '$webappurl',`job`='$job',`gitorstack`= '$gitorstack',`afternoon` = '$afternoon',`aim`='$aim',`status`=2 WHERE `email` = '$mail'  ")){
				$stage = 2;
			}
			else{
				 die("DATABASE Error: " . mysqli_error($con));
			}
		}
	}
	else{
		$error = "Some important details are missing";
	}
}
elseif (isset($_SESSION['mail'])) {
	$mail = $_SESSION['mail'];
	$_mail = isset($_POST['mail'])?mysqli_real_escape_string($con,$_POST['mail']):$_SESSION['mail'];
	$stage =  0;


	if ($mail != $_mail) {
		if($_SESSION['mail'] != $_POST['mail']){
			if(filter_var($_mail, FILTER_VALIDATE_EMAIL)){
		 
				$query = mysqli_query($con,"SELECT * FROM `registeration` WHERE `email` = '".$_mail."'");
				if(mysqli_num_rows($query) > 0){
					$error .= "This mail already exist";
				}
				else{
					if(mysqli_query($con,"UPDATE `registeration` SET `email` ='".$_mail."'")){
						$row = mysqli_fetch_assoc($query);
						$stage = 1;
						if($row['status'] > 0){
							$stage = 2;
						}
						$mail = $_mail;

						$query = mysqli_query($con,"SELECT * FROM `registeration` WHERE `email` = '".$mail."'");
						$row =mysqli_fetch_assoc($query);

						$firstname = $row['firstname'];
						$lastname = $row['lastname'];

						$agree = 1;
						$gender = $row['gender'];
						$company = $row['company'];
						$package = $row['package'];
						$webappurl = $row['webapp'];
						$job = $row['job'];;
						$gitorstack = $row['gitorstack'];
						$afternoon = $row['afternoon'];
						$aim = $row['aim'];


						$stage = 2;
						$mail = $_mail;
						$_SESSION['mail'] = $mail;
					}
					else{
					    die("DATABASE Error: " . mysqli_error($con));
					}
				}
			}
			else{
				$error .= "* Not a valid mail<br/>";
			}
		}

	}
	else{
		$query = mysqli_query($con,"SELECT * FROM `registeration` WHERE `email` = '".$mail."'");
		if(mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_assoc($query);

			$stage = $row['status']>0?2:1;

			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$agree = 1;
			$gender = $row['gender'];
			$company = $row['company'];
			$package = $row['package'];
			$webappurl = $row['webapp'];
			$job = $row['job'];;
			$gitorstack = $row['gitorstack'];
			$afternoon = $row['afternoon'];
			$aim = $row['aim'];
		}
		else{
			$error = "* Some Error occured. Re register again";
			$stage = 0;
		}		
	}

}
	/* TABS */

	$tab_1_a_vis = "none";
	$tab_1_s_vis = "none";
	$tab_2_i_vis = "none";
	$tab_2_a_vis = "none";
	$tab_2_s_vis = "none";
	$tab_3_i_vis = "none";
	$tab_3_a_vis = "none";
	$tab_3_s_vis = "none";

	switch ($stage) {
		case 0:
			$tab_1_a_vis = "block";
			$tab_2_i_vis = "block";
			$tab_3_i_vis = "block";
			break;
		
		case 1:
			$tab_1_s_vis = "block";
			$tab_2_a_vis = "block";
			$tab_3_i_vis = "block";
			break;
		
		case 2:
			$tab_1_s_vis = "block";
			$tab_2_s_vis = "block";
			$tab_3_s_vis = "block";
			break;
		
		default:
			# code...
			break;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register | GDG Cochin DevFest`17 </title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.blue-red.min.css" /> 
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,400,300,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</head>
<body>
	<div class="head">
		<a href="http://gdgcochin.org/" class="gdglogo" style="background-image: url(http://gdgcochin.org/img/sprites/logos.png);background-position: 0 0;width: 82.6px;height: 28px;"></a>
		
		<span style="float: right;height: 28px;line-height: 28px;"><?=$mail?>
			<?php if($mail != ""){
			?>
			<button type="submit" onclick="editmail()" style="margin-left: 10px;" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
			  CHANGE
			</button>
			<?php
			}?>
		</span>
	</div>
	<div class="mobile-head">
		<a href="http://gdgcochin.org/" class="gdglogo" style="background-image: url(http://gdgcochin.org/img/sprites/logos.png);background-position: 0 0;width: 82.6px;height: 28px;"></a>

	</div>
	<div class="infos">
		<div class="mdl-grid">
		  <div class="mdl-cell mdl-cell--8-col">
		  	<span style="font-weight: normal;"><i class="material-icons" style="font-size: 14px;font-weight: bold;">access_time</i>&nbsp;&nbsp;March 31, 2017 8:30 - Dec. 31, 2017 18:00</span>
		  	&nbsp;&nbsp;&nbsp;&nbsp;
		  	<span  style="font-weight: normal;"><i class="material-icons"  style="font-size: 14px;">location_on</i>&nbsp;&nbsp;Kochi</span>
		  	<span class="addtocalender">ADD TO <a href="" style="margin-left: 9px;">Google Calender</a></span>
		  </div>
		  <div class="mdl-cell mdl-cell--4-col">
			  <h5>ADDITIONAL INFORMATION</h5>
			  <span>
			  	<a href="http://www.gdgcochin.org/devfest17">Home</a>, 
			  	<a href="http://www.gdgcochin.org/devfest17#agenda">Agenda</a>, 
			  	<a href="http://www.gdgcochin.org/devfest17#speakers">Speakers</a>, 
			  	<a href="http://www.gdgcochin.org/codeofconduct">Code of Conduct</a>
			  </span>
		  </div>
		</div>		
	</div>
	<div class="main">
		<h1 style="margin: 60px 0 10px;">Registeration</h1>
		<?php
			if($stage == 0){

		?>
		<p dir="ltr">Solve For India&nbsp;are interactive conferences, providing the latest updates on Google technologies and open platforms for developers, startups and industry leaders. Solve For India conferences&nbsp;will be held throughout the year at various cities in India. We're excited to share our thoughts on innovative technologies and encourage developers to build the next big mobile, web, cloud or machine learning solutions.</p>
		<p>Come and join us as we travel around India to help developers build high quality experiences on Google Developer Platform.</p>
		<p>
			<strong><a href="http://www.gdgcochin.org/devfest17#agenda">Agenda</a></strong> | <strong><a href="http://www.gdgcochin.org/speakers">Speakers</a></strong> |&nbsp;<strong><a href="http://www.gdgcochin.org/devfest17">Website</a></strong>
		</p>

		<?php
		}
		?>

		<p>*Confirmation mail will be sent for selected participants and that it is essential for attending the conference.*</p>


		<div class="form">
			<div class="tab active-tab" id="mailtab_a" style="display:<?=$tab_1_a_vis?>">
				<form action="" method="post">
					<div class="mdl-grid">
						<div class="mdl-cell mdl-cell--3-col">
							<span class="tab-number">1</span> <span class="tab-title">Mail Id</span>
						</div>
						<div class="mdl-cell mdl-cell--6-col">
							<h3>required</h3>
							<br/><br/>
							<div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="mail" id="form_mail" value="<?=$mail?>">
									<label class="mdl-textfield__label" for="form_mail">Ente your mail ID*</label>
								</div>
								<?php 
											
								if($error !=""){
									echo "<p class='error'>$error</p>";
								}

								?>
								<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
								  SUBMIT
								</button>
								<br/><br/>
							</div>
						</div>
						<div class="mdl-cell mdl-cell--3-col">
						</div>
					</div>
				</form>
			</div>
			<div class="tab success-tab" id="mailtab_s" style="display:<?=$tab_1_s_vis?>">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col">
						<span class="tab-number"><i class="material-icons">done</i></span>
							<span class="tab-title">Your Email</span>
					</div>
					<div class="mdl-cell mdl-cell--6-col">
						<h3><?=$mail?></h3>
					</div>
					<div class="mdl-cell mdl-cell--3-col">
						<span class="edit_form" onclick="editmail()"><i class="material-icons" style="font-size: 16px;">mode_edit</i> EDIT</span>
					</div>
					
				</div>
			</div>
			<div class="tab inactive-tab" id="detab_i" style="display:<?=$tab_2_i_vis?>">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col">
						<span class="tab-number">2</span>
						<span class="tab-title">Your details</span>
					</div>
					<div class="mdl-cell mdl-cell--6-col">
					</div>
					<div class="mdl-cell mdl-cell--3-col">
					</div>
				</div>
			</div>
			<div class="tab active-tab" id="detab_a" style="display:<?=$tab_2_a_vis?>">
				<form action="" method="POST">
					<div class="mdl-grid">
						<div class="mdl-cell mdl-cell--3-col">
							<span class="tab-number">2</span>
							<span class="tab-title">Your details</span>
						</div>
						<div class="mdl-cell mdl-cell--8-col">
							<h3>required</h3>
							<br/><br/>
							<div>
								<?php 
								
								if($error !=""){
									echo "<p class='error'>$error</p>";
								}

								?>

								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="firstname" id="form_firstname" value="<?=$firstname?>">
									<label class="mdl-textfield__label" for="form_firstname" <?=($stage==1?"focused":"")?>>First name*</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="lastname" id="form_lastname" value="<?=$lastname?>">
									<label class="mdl-textfield__label" for="form_lastname">Last name*</label>
								</div>
								<div class="customform-feild">
									<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect privacy_conf" for="privacy_conf" >
									  <input type="checkbox" id="privacy_conf" name="agree" class="mdl-checkbox__input" <?=($agree==1?"checked":"")?>>
									  <span class="mdl-checkbox__label" style="font-size: 13px;">I acknowledge that my information will be used in accordance with GDG Cochin's Privacy Policy and agree to receive further emails about this event.</span>
									</label>
								</div>
								<div class="customform-feild">
									<label class="custom-label">Gender*</label>

									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_gender_1">
									  <input type="radio" id="form_gender_1" class="mdl-radio__button" name="gender" value="m" <?=($gender=="m"?"checked":"")?>>
									  <span class="mdl-radio__label">Male</span>
									</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_gender_2">
									  <input type="radio" id="form_gender_2" class="mdl-radio__button" name="gender" value="f" <?=($gender=="f"?"checked":"")?>>
									  <span class="mdl-radio__label">Female</span>
									</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_gender_3">
									  <input type="radio" id="form_gender_3" class="mdl-radio__button" name="gender" value="o" <?=($gender=="o"?"checked":"")?>>
									  <span class="mdl-radio__label">Other</span>
									</label>
								</div> 
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="company" id="form_company" value="<?=$company?>">
									<label class="mdl-textfield__label" for="form_company">Company Name*</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="project" id="form_project" value="<?=$package?>">
									<label class="mdl-textfield__label" for="form_project">Your solution's package ID or playstore link or github project repo*</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="webappurl" id="form_webappurl" value="<?=$webappurl?>">
									<label class="mdl-textfield__label" for="form_webappurl">Web App Url of your solution</label>
								</div>
								<div class="customform-feild">
									<label class="custom-label">Which of the below options best describes your day to day job?*</label>

									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_job_1">
									  <input type="radio" id="form_job_2" class="mdl-radio__button" name="job" value="0" <?=($job==0?"checked":"")?>>
									  <span class="mdl-radio__label">Front End Developer</span>
									</label><br/>
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_job_2">
									  <input type="radio" id="form_job_2" class="mdl-radio__button" name="job" value="1" <?=($job==1?"checked":"")?>>
									  <span class="mdl-radio__label">Backend Developer</span>
									</label><br/>
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_job_3">
									  <input type="radio" id="form_job_3" class="mdl-radio__button" name="job" value="2" <?=($job==2?"checked":"")?>>
									  <span class="mdl-radio__label">Mobile Developer</span>
									</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="gitorstack" id="form_gitorstack" value="<?=$gitorstack?>">
									<label class="mdl-textfield__label" for="form_gitorstack">Kindly share a link to your github/stackeoverflow profile* </label>
								</div>

								<?php /*
								<div class="customform-feild">
									<label class="custom-label">Years of professional experience*</label>

									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_exp_1">
									  <input type="radio" id="form_exp_1" class="mdl-radio__button" name="experience" value="2" checked>
									  <span class="mdl-radio__label">0-2 Years</span>
									</label><br/>
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_exp_2">
									  <input type="radio" id="form_exp_2" class="mdl-radio__button" name="experience" value="4">
									  <span class="mdl-radio__label">2-4 Years</span>
									</label><br/>
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_exp_3">
									  <input type="radio" id="form_exp_3" class="mdl-radio__button" name="experience" value="6">
									  <span class="mdl-radio__label">4-6 Years</span>
									</label><br/>
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_exp_4">
									  <input type="radio" id="form_exp_4" class="mdl-radio__button" name="experience" value="66">
									  <span class="mdl-radio__label">6+ Years</span>
									</label>
								</div> 

								<div class="customform-feild">
									<label class="custom-label">Which of the below tools/platforms do you use on regular basis?*</label>

									<?php 
										$tools = ["Crashlytics","Answers","AppsFlyer","Flurry","MixPanel","Firebase","GCP","Parse"];
										foreach ($tools as $key => $value) {
									?>
									<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="form_tools_<?=$key?>">
										<input type="checkbox" id="form_tools_<?=$key?>" name="tools<?=$key?>" class="mdl-checkbox__input">
										<span class="mdl-checkbox__label"><?=$value?></span>
									</label><br/>
									<?php
										}
									?>
								</div>

								*/ ?>

								<div class="customform-feild">
									<label class="custom-label">Which Track you want to Sit in Afternoon ? *</label>

									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_afternoon_1">
									  <input type="radio" id="form_afternoon_1" class="mdl-radio__button" name="afternoon" value="an" <?=($afternoon=="an"?"checked":"")?>>
									  <span class="mdl-radio__label">Android</span>
									</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="form_afternoon_3">
									  <input type="radio" id="form_afternoon_3" class="mdl-radio__button" name="afternoon" value="ml" <?=($afternoon=="ml"?"checked":"")?>>
									  <span class="mdl-radio__label">Machine Learning</span>
									</label>
								</div>

								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input class="mdl-textfield__input" type="text" name="aim" id="form_aim" value="<?=$aim?>">
									<label class="mdl-textfield__label" for="form_aim">What you would like to learn from this event? *</label>
								</div>

								<?php 
								
								if($error !=""){
									echo "<p class='error'>$error</p>";
								}

								?>

								<input type="hidden" name="details">
								<br/><br/><br/>
								<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
								  SUBMIT
								</button>
							</div>
						</div>
					</div>
				</form>	
			</div>
			<div class="tab success-tab" id="detab_s" style="display:<?=$tab_2_s_vis?>">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col">
						<span class="tab-number"><i class="material-icons">done</i></span>
						<span class="tab-title">Your details</span>
					</div>
					<div class="mdl-cell mdl-cell--6-col">
						<h3><?=$firstname?> <?=$lastname?></h3>
					</div>
					<div class="mdl-cell mdl-cell--3-col">
						<span class="edit_form" onclick="editform()"><i class="material-icons" style="font-size: 16px;">mode_edit</i> EDIT</span>
					</div>
				</div>
			</div>
			<div class="tab inactive-tab" id="conftab_i" style="display:<?=$tab_3_i_vis?>">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col">
						<span class="tab-number">3</span>
						<span class="tab-title">Confirmation</span>
					</div>
					<div class="mdl-cell mdl-cell--6-col">
					</div>
					<div class="mdl-cell mdl-cell--3-col">
					</div>
				</div>
			</div>
			<div class="tab success-tab" id="conftab_s" style="display:<?=$tab_3_s_vis?>">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col">
						<span class="tab-number"><i class="material-icons">done</i></span>
						<span class="tab-title">Confirmation</span>
					</div>
					<div class="mdl-cell mdl-cell--6-col">
						<?php
							if(isset($_POST['details'])){
								echo "<h3 style=\"font-size: 15px;\">Thanks. That's been saved, and you will receive an email shortly.</h3>";
							}
							else{
								echo "<h3 style=\"font-size: 15px;\">This mail is already registered. You will recive an email shortly<h3>";
							}
						?>
					</div>
					<div class="mdl-cell mdl-cell--3-col">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<a href="http://gdgcochin.org/" class="gdglogo" style="background-image: url(http://gdgcochin.org/img/sprites/logos.png);background-position: 0 -40px;width: 118px;height: 40px;"></a>
	</div>
	<div class="back-tab" id="back-tab">
		<a href="#back" id="back"><i class="material-icons" style="font-size: 25px;">arrow_back</i></a>
	</div>
	<script type="text/javascript">
		window.onscroll = function() {setBack()};
		function setBack() {
		    if (document.body.scrollTop > 64 || document.documentElement.scrollTop > 64) {
		        document.getElementById("back-tab").className = "back-tab fixed";
		    } else {
		        document.getElementById("back-tab").className = "back-tab";
		    }
		}
		function editmail(){
			document.getElementById("mailtab_a").style.display = "block";
			document.getElementById("mailtab_s").style.display = "none";

			document.getElementById("detab_i").style.display = "block";
			document.getElementById("detab_a").style.display = "none";
			document.getElementById("detab_s").style.display = "none";

			document.getElementById("conftab_i").style.display = "block";
			document.getElementById("conftab_s").style.display = "none";

			document.getElementById("mailtab_a").getElementsByTagName("input")[0].focus();
		}
		function editform(){
			document.getElementById("mailtab_a").style.display = "none";
			document.getElementById("mailtab_s").style.display = "block";

			document.getElementById("detab_i").style.display = "none";
			document.getElementById("detab_a").style.display = "block";
			document.getElementById("detab_s").style.display = "none";

			document.getElementById("conftab_i").style.display = "block";
			document.getElementById("conftab_s").style.display = "none";

			document.getElementById("detab_a").getElementsByTagName("input")[0].focus();
		}
	</script>
</body>
</html>
