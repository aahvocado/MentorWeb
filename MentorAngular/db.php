<?php
	include 'db_helper.php';
	header("Access-Control-Allow-Origin: *");

	function welcome() {
		global $_USER;

		//$userid = array('username' => $_USER['uid']);
		//echo var_dump($_USER);
		$userInfo = array('username' => $_USER['uid']);
		$userType = "None";
		
		$dbQuery = sprintf("SELECT first_name, last_name FROM User WHERE username = '%s'",
												$_USER['uid']);
		$result = getDBResultsArray($dbQuery);
		if (!empty($result)) {
			$userType = "User";
			$userInfo["firstName"] = $result["0"]["first_name"];
			$userInfo["lastName"] = $result["0"]["last_name"];
		}

		if (!empty($result)) {
			$dbQuery = sprintf("SELECT username FROM Mentee WHERE username = '%s'",
													$_USER['uid']);
			$result = getDBResultsArray($dbQuery);
			if (!empty($result)){$userType = "Mentee";}
		}

		if (empty($result)) {
			$dbQuery = sprintf("SELECT username FROM Mentor WHERE username = '%s'",
													$_USER['uid']);
			$result = getDBResultsArray($dbQuery);
			if (!empty($result)){$userType = "Mentor";}
		}

		if (empty($result)) {
			$dbQuery = sprintf("SELECT username FROM Admin WHERE username = '%s'",
													$_USER['uid']);
			$result = getDBResultsArray($dbQuery);
			if (!empty($result)){$userType = "Admin";}
		}
		// echo $userType;
		// array_push($result, $userType);
		 $userInfo["userType"] = $userType;
		// echo var_dump($_User);
		// echo $result["0"]["UserType"];

	 	 $GLOBALS["_PLATFORM"]->sandboxHeader("Content-type: application/json");
	 	 //echo var_dump($userinfo);
	 	 echo json_encode($userInfo);
	}//end welcome

	function getMentor() {

	}

	function getUserType() {
		// echo "in getUserType: \n";
		global $_USER;
		$user = $_USER['uid'];

		$userInfo = array("Admin" => 0, "Mentor" => 0, "Mentee" => 0, "Name" => '', "Id" => '', "Mentor" => '');
		$checkAdmin = sprintf("SELECT first_name, id FROM User, Admin WHERE User.username = '%s' AND Admin.username = '%s'", $user, $user);
		$isAdmin = getDBResultsArray($checkAdmin);
		// echo "isAdmin: " . $isAdmin . "\n";
		if (!empty($isAdmin)) {
			$userInfo["Admin"] = 1;
			$userInfo["Name"] = $isAdmin[0]["first_name"];
			$userInfo["Id"] = $isAdmin[0]["id"];
		}
		$checkMentor = sprintf("SELECT first_name, id FROM User, Mentor WHERE User.username = '%s' AND Mentor.username = '%s'", $user, $user);
		$isMentor = getDBResultsArray($checkMentor);
		if (!empty($isMentor)) {
			$userInfo["Mentor"] = 1;
			$userInfo["Name"] = $isMentor[0]["first_name"];
			$userInfo["Id"] = $isMentor[0]["id"];
		}
		$checkMentee = sprintf("SELECT first_name, id, mentor_user FROM User, Mentee WHERE User.username = '%s' AND Mentee.username = '%s'", $user, $user);
		$isMentee = getDBResultsArray($checkMentee);
		if (!empty($isMentee)) {
			$userInfo["Mentee"] = 1;
			$userInfo["Name"] = $isMentee[0]["first_name"];
			$userInfo["Id"] = $isMentee[0]["id"];
			$userInfo["Mentor"] = $isMentee[0]["mentor_user"];
		}
		header("Content-type: application/json");
		echo json_encode($userInfo);
	}

	function submitRegForm($form) {
		global $_USER;

		$dbQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
												VALUES ('%s', '%s', '%s', '%u', '%s', '%s')",
												$_USER['uid'], $form['firstName'], $form['lastName'], 
												$form['phoneNumber'], $form['email'], $form['commMethod']);
		$result = getDBRegInserted($dbQuery);

		header("Content-type: application/json");
		echo json_encode($result);
	}//end submitRegForm

	function listMentor(){
		echo "list Mentor";

	}

	function addMentee() {
		echo "addMentee \n";
		global $_USER;	
		$user = $_USER['uid'];
		$fname = mysql_real_escape_string($_POST['fname']);
		echo "first: " . $fname . "\n";
		//echo var_dump($_POST['fname']);
		$lname = mysql_real_escape_string($_POST['lname']);
		echo "last: " . $lname . "\n";
		$phone = mysql_real_escape_string($_POST['phone']);
		echo "phone: " . $phone . "\n";
		$email = mysql_real_escape_string($_POST['email']);
		echo "email: " . $email . "\n";
		$pref_comm = mysql_real_escape_string($_POST['pref_comm']);
		echo "pref_comm: " . $pref_comm . "\n";
		$depth_focus = mysql_real_escape_string($_POST['dfocus']);
		echo "depth_focus: " . $depth_focus . "\n";
		$depth_focus_other = mysql_real_escape_string($_POST['dfocusother']); //don't need escape string for pre-defined vals
		echo "depth_focus_other: " . $depth_focus_other . "\n";
		$first_gen_college_student = $_POST['first_gen_college_student'];
		echo "first_gen: " . $first_gen_college_student . "\n";
		$transfer_from_outside = $_POST['transfer_from_outside'];
		echo "transfer outside: " . $transfer_from_outside . "\n";
		$institution_name = mysql_real_escape_string($_POST['institution_name']);
		echo "inst name: " . $institution_name . "\n";
		$transfer_from_within = $_POST['transfer_from_within'];
		$prev_major = mysql_real_escape_string($_POST['prev_major']);
		echo "prev_major: " . $prev_major . "\n";
		$international_student = $_POST['international_student'];
		echo "international student :" . $international_student . "\n";
		$expec_graduation = mysql_real_escape_string($_POST['expec_graduation']);
		$other_major =  mysql_real_escape_string($_POST['other_major']);
		$breadth_track = "Big json";//is a json, need desc. to go along with it $_POST['breadth_track']  json_decode($json)
		$undergrad_research = $_POST['undergrad_research'];
		$bme_organization = "bme organizations"; //Json of all the organizations $_POST['bme_organization']
		$tutor_teacher_program = "programs checkbox";//JSON from checkbox;
		$bme_academ_exp = "json checkbox"; //json
		$international_experience = "josn checkbox";//json
		$career_dev_program = "json checkbox";
		$career_dev_program_desc = mysql_real_escape_string($_POST['career_dev_program_desc']);
		$post_grad_plan = "json radio button";
		$post_grad_plan_desc = mysql_real_escape_string($_POST['post_grad_plan_desc']);
		$freshman = 1;
		$personal_hobby = mysql_real_escape_string($_POST['personal_hobby']);

		$userQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s', '%s','%s','%s')", $user, $lname, $fname,$phone,$email,$pref_comm);
		$uresult = getDBRegInserted($userQuery);

		$menteeQuery = sprintf("INSERT INTO Mentee (username, depth_focus, depth_focus_other, post_grad_plan, post_grad_plan_desc, 
			freshman, transfer_from_outside, institution_name,
			transfer_from_within, prev_major, international_student, first_gen_college_student, expec_graduation, 
			undergrad_research,  personal_hobby) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%u', '%u', '%s', '%u', '%s', '%u', '%u', '%s', '%u', '%s')", 
			$user, $depth_focus, $depth_focus_other, $post_grad_plan, $post_grad_plan_desc, 
			$freshman, $transfer_from_outside, $institution_name,
			$transfer_from_within, $prev_major, $international_student, $first_gen_college_student, $expec_graduation, 
			$undergrad_research, $personal_hobby);
		$mresult = getDBRegInserted($menteeQuery);


		// $menteeQuery = sprintf("INSERT INTO Mentee (username, gender, opt_in, depth_focus, depth_focus_other,
		// 	live_before_tech, live_on_campus, first_gen_college_student, transfer_from_outside, institution_name,
		// 	transfer_from_within, prev_major, international_student, home_country, expec_graduation, other_major, 
		// 	undergrad_research, undergrad_research_desc, post_grad_plan, post_grad_plan_desc, personal_hobby) 
		// 	VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%u', '%u', '%u', '%s', '%u', '%s', '%s', '%s', '%s', '%s', 
		// 		'%u', '%s', '%s', '%s', '%s')", 
		// 	$user, $gender, $opt_in, $depth_focus, $depth_focus_other,
		// 	$live_before_tech, $live_on_campus, $first_gen_college_student, $transfer_from_outside, $institution_name, 
		// 	$transfer_from_within, $prev_major, $international_student, $home_country, $expec_graduation, $other_major,
		// 	$undergrad_research, $undergrad_research_desc, $post_grad_plan, $post_grad_plan_desc, $personal_hobby);

			// , 
			// post_grad_plan, post_grad_plan_desc, , , , 
			// , , , , , 
			// , , undergrad_research, undergrad_research_lab_worked, 
			// undergrad_research_num_semesters, home_country, personal_hobby
				// '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
			 // '%s', '%s', '%s', '%s', '%s', '%s', '%s'
		// $mresult = getDBRegInserted($mentorQuery);

		// $btrackQuery = sprintf("INSERT INTO Mentor_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
		// 	$user, $breadth_track, $breadth_track_desc);
		// $btrackresult = getDBRegInserted($btrackQuery);

		// $bmeOrgQuery = sprintf("INSERT INTO Mentee_BME_Organization(username, bme_org1, bme_org2, bme_org3,
		// 	bme_org4, bme_org5, bme_org6, bme_org7, bme_org8) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s', '%s')",
		// 	$user, $bme_organization, $bme_organization_other);
		// $bmeOrgresult = getDBRegInserted($bmeOrgQuery);

		// $bmeQuery = sprintf("INSERT INTO Mentee_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
		// 	bme_academ_exp3, bme_academ_exp4, bme_academ_exp_desc) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
		// $user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4, $bme_academ_exp_desc);
		// $bmeresult = getDBRegInserted($bmeQuery);

		// $interQuery = sprintf("INSERT INTO Mentee_International_Experience(username, international_experience1, international_experience2, 
		// 	international_experience3, international_experience4, international_experience5, international_experience_desc)
		// VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
		// $international_experience3, $international_experience4, $international_experience4, $international_experience_desc);
		// $interresults = getDBRegInserted($interQuery);

		// $careerQuery = sprintf("INSERT INTO Mentee_Career_Dev_Program(username, career_dev_program1,
		// 	career_dev_program2, career_dev_program3, career_dev_program_desc) VALUES ('%s', '%s', 
		// 	'%s','%s','%s')", $user, $career_dev_program1, $career_dev_program2, $career_dev_program3,
		// $career_dev_program_desc);
		// $careerresults = getDBRegInserted($careerQuery);
		header("Content-type: application/json");
		// print_r($json);
		echo json_encode($uresult);
		echo json_encode($mresult);
	}

	function addMentor() {
		echo "addMEntor \n";
		global $_USER;	
		// $data = file_get_contents("php://input");

		//Should i use mysqli or mysql?
		//does casting true and false to int work? Do I even need to cast?
		//are we doing true/false right by casting to int?
		$user = $_USER['uid'];
		echo "user: " . $user . "\n";
		$fname = mysql_real_escape_string($_POST['fname']);//$data->fname);
		echo "first: " . $fname . "\n";
		//echo var_dump($_POST['fname']);
		$lname = mysql_real_escape_string($_POST['lname']);
		echo "last: " . $lname . "\n";
		$phone = mysql_real_escape_string($_POST['phone']);
		echo "phone: " . $phone . "\n";
		$email = mysql_real_escape_string($_POST['email']);
		echo "email: " . $email . "\n";
		$pref_comm = mysql_real_escape_string($_POST['pref_comm']);
		echo "pref_comm: " . $pref_comm . "\n";
		$gender = mysql_real_escape_string($_POST['gender']);
		echo "gender: " . $gender . "\n";
		$ethnicity = json_decode($_POST['ethnicity'], true);
		echo "ethnicity: " . $ethnicity . "\n";
		for ($i=1; $i < count($ethnicity) ; $i++) {
			${"ethnicity" . $i}  = $ethnicity[$i]['name'];
			echo "Name " . ${"ethnicity" . $i} ;
		}
		$depth_focus = mysqli_real_escape_string($_POST['dfocus']);
		$depth_focus_other = mysqli_real_escape_string($_POST['dfocusother']); //don't need escape string for pre-defined vals
		$live_before_tech = mysqli_real_escape_string($_POST['live_before_tech']);
		$live_on_campus = (int)$_POST['live_on_campus'];
		$first_gen_college_student = (int)$_POST['first_gen_college_student'];
		$transfer_from_outside = (int)$_POST['transfer_from_outside'];
		$institution_name = mysqli_real_escape_string($_POST['$institution_name']);
		$transfer_from_within = (int)$_POST['transfer_from_within'];
		$prev_major = mysqli_real_escape_string($_POST['$prev_major']);
		$international_student = mysqli_real_escape_string($_POST['$international_student']);
		$home_country =  mysqli_real_escape_string($_POST['$home_country']);
		$expec_graduation = mysqli_real_escape_string($_POST['$expec_graduation']);
		$honor_program = "honorProgram"; //is a json $_POST['honor_program']
		$other_major =  mysqli_real_escape_string($_POST['$other_major']);
		$breadth_track = "Big json";//is a json, need desc. to go along with it $_POST['breadth_track']  json_decode($json)
		$undergrad_research = (int)$_POST['undergrad_research'];
		$undergrad_research_desc = $_POST['$undergrad_research_desc'];
		$other_organization = "other orgs";//form.other_organization.one other_organization.two other_organization.three but only sent through form.other_organization
		$bme_organization = "bme organizations"; //Json of all the organizations $_POST['bme_organization']
		$bme_organization_other = mysqli_real_escape_string($_POST['$bme_organization_other']);
		$mentee_mentor_organization = "json of mentee mentor orgs"; //Json from checkbox
		$mentee_mentor_organization_other = mysqli_real_escape_string($_POST['$mentee_mentor_organization_other']);
		$tutor_teacher_program = "programs checkbox";//JSON from checkbox;
		$tutor_teacher_program_desc = mysqli_real_escape_string($_POST['$tutor_teacher_program_desc']);
		$bme_academ_exp = "json checkbox"; //json
		$bme_academ_exp_desc = mysql_real_escape_string($_POST['$bme_academ_exp_desc']);
		$international_experience = "josn checkbox";//json
		$international_experience_desc = mysql_real_escape_string($_POST['international_experience_desc']);
		$career_dev_program = "json checkbox";
		$career_dev_program_desc = mysql_real_escape_string($_POST['career_dev_program_desc']);
		$post_grad_plan = "json radio button";
		$post_grad_plan_desc = mysql_real_escape_string($_POST['post_grad_plan_desc']);
		$personal_hobby = mysql_real_escape_string($_POST['personal_hobby']);

		//or request.form  form.get()

		// $userQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
		// 			VALUES ('%s', '%s', '%s','%s','%s','%s')", $_USER['uid'], $lname, $fname,$phone,$email,$pref_comm);
		// $uresult = getDBRegInserted($userQuery);


		// $mentorQuery = sprintf("INSERT INTO Mentor (username, gender, opt_in, depth_focus, depth_focus_other,
		// 	live_before_tech, live_on_campus, first_gen_college_student, transfer_from_outside, institution_name,
		// 	transfer_from_within, prev_major, international_student, home_country, expec_graduation, other_major, 
		// 	undergrad_research, undergrad_research_desc, post_grad_plan, post_grad_plan_desc, personal_hobby) 
		// 	VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%u', '%u', '%u', '%s', '%u', '%s', '%s', '%s', '%s', '%s', 
		// 		'%u', '%s', '%s', '%s', '%s')", 
		// 	$user, $gender, $opt_in, $depth_focus, $depth_focus_other,
		// 	$live_before_tech, $live_on_campus, $first_gen_college_student, $transfer_from_outside, $institution_name, 
		// 	$transfer_from_within, $prev_major, $international_student, $home_country, $expec_graduation, $other_major,
		// 	$undergrad_research, $undergrad_research_desc, $post_grad_plan, $post_grad_plan_desc, $personal_hobby);

			// , 
			// post_grad_plan, post_grad_plan_desc, , , , 
			// , , , , , 
			// , , undergrad_research, undergrad_research_lab_worked, 
			// undergrad_research_num_semesters, home_country, personal_hobby
				// '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
			 // '%s', '%s', '%s', '%s', '%s', '%s', '%s'
		// $mresult = getDBRegInserted($mentorQuery);

		// $ethQuery = sprintf("INSERT INTO Ethnicity(username, ethnicity1, ethnicity2, ethnicity3, ethnicity4, ethnicity5) VALUES ()", $ethnicity);
		// $eresult = getDBRegInserted($ethQuery);

		// $honorProgQuery = sprintf("INSERT INTO Mentor_Honors_Program(username, program1, program2, program3) VALUES ()", $honor_program);
		// $hpresult = getDBRegInserted($honorProgQuery);

		// $btrackQuery = sprintf("INSERT INTO Mentor_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
		// 	$user, $breadth_track, $breadth_track_desc);
		// $btrackresult = getDBRegInserted($btrackQuery);

		// $otherOrgQuery = sprintf("INSERT INTO Other_Organization(username, organization1, organization2, organization3) 
		// 	VALUES('%s', '%s', '%s', '%s')", $user, $other_organization.one, $other_organization.two, $other_organization.three);
		// $ooresult = getDBRegInserted($otherOrgQuery);

		// $bmeOrgQuery = sprintf("INSERT INTO Mentor_BME_Organization(username, bme_org1, bme_org2, bme_org3,
		// 	bme_org4, bme_org5, bme_org6, bme_org7, bme_org8) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s', '%s')",
		// 	$user, $bme_organization, $bme_organization_other);
		// $bmeOrgresult = getDBRegInserted($bmeOrgQuery);

		// $mmOrgQuery = sprintf("INSERT INTO Mentee_Mentor_Organization(username, mm_org1, mm_org2, mm_org3, mm_org4, mm_org5, mm_org6), 
		// 	VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $mentee_mentor_organization, $mentee_mentor_organization_other);
		// $mmresult = getDBRegInserted($mmOrgQuery);

		// $bmeQuery = sprintf("INSERT INTO Mentor_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
		// 	bme_academ_exp3, bme_academ_exp4, bme_academ_exp_desc) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
		// $user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4, $bme_academ_exp_desc);
		// $bmeresult = getDBRegInserted($bmeQuery);

		// $interQuery = sprintf("INSERT INTO Mentor_International_Experience(username, international_experience1, international_experience2, 
		// 	international_experience3, international_experience4, international_experience5, international_experience_desc)
		// VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
		// $international_experience3, $international_experience4, $international_experience4, $international_experience_desc);
		// $interresults = getDBRegInserted($interQuery);

		// $careerQuery = sprintf("INSERT INTO Mentor_Career_Dev_Program(username, career_dev_program1,
		// 	career_dev_program2, career_dev_program3, career_dev_program_desc) VALUES ('%s', '%s', 
		// 	'%s','%s','%s')", $user, $career_dev_program1, $career_dev_program2, $career_dev_program3,
		// $career_dev_program_desc);
		// $careerresults = getDBRegInserted($careerQuery);

		// //header("Content-type: application/json");
		// // print_r($json);
		// echo json_encode($uresult+$mresult);
		
	}//end addMentor

	function genFauxUsers($form) {
		global $_USER;

		//$form = json_decode($form);
		$count = 0;
		foreach($form as $currentUser) {
			$count++;
			$dbQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
													VALUES ('%s', '%s', '%s', '%u', '%s', '%s')",
													$currentUser['uid'], $currentUser['firstName'], $currentUser['lastName'], 
													$currentUser['phoneNumber'], $currentUser['email'], $currentUser['commMethod']);
			$result = getDBRegInserted($dbQuery);
		}

		// $dbQuery = sprintf("first_name = $form['firstName'],
		//  last_name=$form['lastName'], phone_num=$form['phoneNumber'], email=$form['email'],
		//   pref_communication=$form['commMethod']");

		header("Content-type: application/json");
		//echo $form;
		echo json_encode($count);
	}//end genFauxUsers

	function addToWishlist($mentorUsername) {
		global $_USER;

		$dbQuery = sprintf("SELECT mentor FROM Wishlist WHERE username = '%s'",
												$mentorUsername['uid']);
		$result = getDBResultsArray($dbQuery);
		
		if (!empty($result)) {
			$dbQuery = sprintf("INSERT INTO Wishlist (mentee, mentor)
													VALUES ('%s', '%s')",
													$_USER['uid'], $username['uid']);
			$result = getDBRegInserted($dbQuery);
		}
	}
	
	function listComments() {
		global $_USER;
		// $userid = array('username' => $_USER['uid']);

		$dbQuery = sprintf("SELECT id,comment FROM usercomments");
		$result = getDBResultsArray($dbQuery);
		error_log("test");
		header("Content-type: application/json");
		echo json_encode($userid);
	}
	
	function getComment($id) {
		$dbQuery = sprintf("SELECT id,comment FROM usercomments WHERE id = '%s'",
			mysql_real_escape_string($id));
		$result=getDBResultRecord($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	function addComment($comment) {
		
		global $_USER;
		$user = $_USER["uid"];
		$dbQuery = sprintf("INSERT INTO usercomments (comment, user) VALUES ('%s', '%s')",
			mysql_real_escape_string($comment), mysql_real_escape_string($user));
	
		$result = getDBResultInserted($dbQuery,'personId');
		
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function checkCommentPermission($id) {
		global $_USER;
		$user = $_USER["uid"];
		$dbQuery = sprintf("SELECT user from usercomments where id = '%s'",
					mysql_real_escape_string($id));
		$result = getDBResultRecord($dbQuery);
		if ($result["user"] != $user) {
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 401 Unauthorized');
			die();
		}
	}

	function updateComment($id,$comment) {

		checkCommentPermission($id);
		$dbQuery = sprintf("UPDATE usercomments SET comment = '%s' WHERE id = '%s'",
			mysql_real_escape_string($comment),
			mysql_real_escape_string($id));
		
		$result = getDBResultAffected($dbQuery);
		
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	function deleteComment($id) {
		checkCommentPermission($id);
		$dbQuery = sprintf("DELETE FROM usercomments WHERE id = '%s'",
			mysql_real_escape_string($id));												
		$result = getDBResultAffected($dbQuery);
		
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function listUsers() {
		$dbQuery = sprintf("SELECT DISTINCT(user) from usercomments");
		$result = getDBResultsArray($dbQuery);
		error_log("test");
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function getUserComment($user, $id) {
		$dbQuery = sprintf("SELECT id,comment FROM usercomments WHERE user = '%s' AND id = '%s'",
			mysql_real_escape_string($user), mysql_real_escape_string($id));
		$result=getDBResultRecord($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function listUserComments($user, $id) {
		$dbQuery = sprintf("SELECT id,comment FROM usercomments WHERE user = '%s'",
			mysql_real_escape_string($user));
		$result=getDBResultsArray($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}
?>