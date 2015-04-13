<?php
	include_once('db_helper.php');
	header("Access-Control-Allow-Origin: *");

	function welcome() {
		global $_USER;

		//$userid = array('username' => $_USER['uid']);
		//echo var_dump($_USER);
		$userInfo = array('username' => $_USER['uid']);
		$userType = "None";
		
		$dbQuery = sprintf("SELECT first_name, last_name FROM USER WHERE username = '%s'",
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

		if (empty($result) && userIsAdmin()) {
			$userType = "Admin";
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

	//function reset() {
		//global $_USER;

		// $dbQuery = sprintf("SELECT first_name, last_name FROM User WHERE username = '%s'",
		// 										$_USER['uid']);
		// $result = getDBResultsArray($dbQuery
		// $dbQuery = sprintf("SELECT first_name, last_name FROM User WHERE username = '%s'",
		// 										$_USER['uid']);
		// $result = getDBResultsArray($dbQuery
		// $dbQuery = sprintf("DELETE FROM Mentee WHERE username = '%s'",
		// 										$_USER['uid']);
		// $result = getDBResultsArray($dbQuery);
		// $dbQuery = sprintf("DELETE FROM User WHERE username = '%s'",
		// 										$_USER['uid']);
		// $result = getDBResultsArray($dbQuery);
	//}

	function resetUser() {
		global $_USER;
		$dbQuery = sprintf("DELETE FROM USER WHERE username = '%s'",
												$_USER['uid']);
		$result = getDBResultsArray($dbQuery);

		$userInfo = array('username' => $_USER['uid']);
		$userInfo['complete'] = "true";
		echo json_encode($userInfo);
	}

	function getUserType() {
		// echo "in getUserType: \n";
		global $_USER;
		$user = $_USER['uid'];

		$userInfo = array("Admin" => 0, "Mentor" => 0, "Mentee" => 0, "Name" => '',"Mentor" => '');
		$checkAdmin = sprintf("SELECT first_name FROM USER, Admin WHERE USER.username = '%s' AND Admin.username = '%s'", $user, $user);
		$isAdmin = getDBResultsArray($checkAdmin);
		// echo "isAdmin: " . $isAdmin . "\n";
		if (!empty($isAdmin)) {
			$userInfo["Admin"] = 1;
			$userInfo["Name"] = $isAdmin[0]["first_name"];
		}
		$checkMentor = sprintf("SELECT first_name FROM USER, Mentor WHERE USER.username = '%s' AND Mentor.username = '%s'", $user, $user);
		$isMentor = getDBResultsArray($checkMentor);
		if (!empty($isMentor)) {
			$userInfo["Mentor"] = 1;
			$userInfo["Name"] = $isMentor[0]["first_name"];
		}
		$checkMentee = sprintf("SELECT first_name, mentor_user FROM USER, Mentee WHERE USER.username = '%s' AND Mentee.username = '%s'", $user, $user);
		$isMentee = getDBResultsArray($checkMentee);
		if (!empty($isMentee)) {
			$userInfo["Mentee"] = 1;
			$userInfo["Name"] = $isMentee[0]["first_name"];
			$userInfo["Mentor"] = $isMentee[0]["mentor_user"];
		}
		header("Content-type: application/json");
		echo json_encode($userInfo);
	}

	function submitRegForm($form) {
		global $_USER;

		$dbQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
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
		global $_USER;	
		$user = $_USER['uid'];
		$fname = mysql_real_escape_string($_POST['fname']);
		$lname = mysql_real_escape_string($_POST['lname']);
		$phone = mysql_real_escape_string($_POST['phone']);
		$email = mysql_real_escape_string($_POST['email']);
		$pref_comm = mysql_real_escape_string($_POST['pref_comm']);
		$depth_focus = mysql_real_escape_string($_POST['dfocus']);
		$depth_focus_other = mysql_real_escape_string($_POST['dfocusother']); //don't need escape string for pre-defined vals
		$first_gen_college_student = $_POST['first_gen_college_student'];
		$transfer_from_outside = $_POST['transfer_from_outside'];
		$institution_name = mysql_real_escape_string($_POST['institution_name']);
		$transfer_from_within = $_POST['transfer_from_within'];
		$prev_major = mysql_real_escape_string($_POST['prev_major']);
		$international_student = $_POST['international_student'];
		$expec_graduation = mysql_real_escape_string($_POST['expec_graduation']);
		$other_major =  mysql_real_escape_string($_POST['other_major']);
		$undergrad_research =  mysql_real_escape_string($_POST['undergrad_research']);

		$bme_org1 = null;
		$bme_org2 = null;
		$bme_org3 = null;
		$bme_org4 = null;
		$bme_org5 = null;
		$bme_org6 = null;
		$bme_org7 = null;
		$bmeOrgs = $_POST['bme_organization'];
		for ($i=1; $i <= count($bmeOrgs); $i++) {
			${"bme_org" . $i}  = $bmeOrgs[$i-1]['name']; //Json of all the organizations $_POST['bme_organization']
		}

		$tutor_teacher_program1 = null;
		$tutor_teacher_program2 = null;
		$tutor_teacher_program3 = null;
		$tutor_teacher_program4 = null;
		$tutor_teacher_program5 = null;
		$tutor_teacher_program6 = null;
		$ttProg = $_POST['tutor_teacher_program'];
		for ($i=1; $i <= count($ttProg); $i++) {
			${"tutor_teacher_program" . $i}  = $ttProg[$i-1]['name']; 
		}

		$bme_academ_exp1 = null;
		$bme_academ_exp2 = null;
		$bme_academ_exp3 = null;
		$bme_academ_exp4 = null;
		$bmeExp = $_POST['bme_academ_exp'];
		for ($i=1; $i <= count($bmeExp); $i++) {
			${"bme_academ_exp" . $i}  = $bmeExp[$i-1]['name']; 
		}

		$international_experience1 = null;
		$international_experience2 = null;
		$international_experience3 = null;
		$international_experience4 = null;
		$international_experience5 = null;
		$internatExp = $_POST['international_experience'];
		for ($i=1; $i <= count($internatExp); $i++) {
			${"international_experience" . $i}  = $internatExp[$i-1]['name']; 
		}

		$career_dev_program1 = null;
		$career_dev_program2 = null;
		$career_dev_program3 = null;
		$carDevProg = $_POST['career_dev_program']; 
		for ($i=1; $i <= count($carDevProg); $i++) {
			${"career_dev_program" . $i}  = $carDevProg[$i-1]['name']; 
		}

		$post_grad_plan = mysql_real_escape_string($_POST['post_grad_plan']);
		$post_grad_plan_desc = mysql_real_escape_string($_POST['post_grad_plan_desc']);
		$personal_hobby = mysql_real_escape_string($_POST['personal_hobby']);

		$userQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s', '%s','%s','%s')", $user, $lname, $fname,$phone,$email,$pref_communication);
		$uResult = getDBRegInserted($userQuery);

		$menteeQuery = sprintf("INSERT INTO Mentee (username, depth_focus, depth_focus_other, post_grad_plan, post_grad_plan_desc, 
			freshman, transfer_from_outside, institution_name,
			transfer_from_within, prev_major, international_student, first_gen_college_student, expec_graduation, 
			undergrad_research,  personal_hobby) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%u', '%u', '%s', '%u', '%s', '%u', '%u', '%s', '%u', '%s')", 
			$user, $depth_focus, $depth_focus_other, $post_grad_plan, $post_grad_plan_desc, 
			$freshman, $transfer_from_outside, $institution_name,
			$transfer_from_within, $prev_major, $international_student, $first_gen_college_student, $expec_graduation, 
			$undergrad_research, $personal_hobby);
		$mResult = getDBRegInserted($menteeQuery);

		$bTrack = $_POST['breadth_track'];
		foreach ($bTrack as $key => $value) {
			$breadth_track = $value['name'];
			$breadth_track_desc = $value['desc'];
			$btrackQuery = sprintf("INSERT INTO Mentee_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
			$user, $breadth_track, $breadth_track_desc);
			$btrackResult = getDBRegInserted($btrackQuery);
		}

		if ($_POST['bme_organization']) {
			$bmeOrgQuery = sprintf("INSERT INTO Mentee_BME_Organization(username, bme_org1, bme_org2, bme_org3, 
				bme_org4, bme_org5, bme_org6, bme_org7) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s')",
				$user, $bme_org1, $bme_org2, $bme_org3, $bme_org4, $bme_org5, $bme_org6, $bme_org7);
			$bmeOrgResult = getDBRegInserted($bmeOrgQuery);
		}

		if ($_POST['bme_academ_exp']) {
			$bmeQuery = sprintf("INSERT INTO Mentee_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
				bme_academ_exp3, bme_academ_exp4) VALUES ('%s', '%s', '%s', '%s', '%s')",
			$user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4);
			$bmeResult = getDBRegInserted($bmeQuery);
		}

		if ($_POST['international_experience']) {
			$interQuery = sprintf("INSERT INTO Mentee_International_Experience(username, international_experience1, international_experience2, 
				international_experience3, international_experience4, international_experience5)
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
			$international_experience3, $international_experience4, $international_experience5);
			$interResult = getDBRegInserted($interQuery);
		}

		if ($_POST['career_dev_program']) {
			$careerQuery = sprintf("INSERT INTO Mentee_Career_Dev_Program(username, career_dev_program1,
				career_dev_program2, career_dev_program3) VALUES ('%s', '%s', '%s','%s')",
				$user, $career_dev_program1, $career_dev_program2, $career_dev_program3);
			$careerResult = getDBRegInserted($careerQuery);
		}

		if ($_POST['tutor_teacher_program']) {
			$ttProgQuery = sprintf("INSERT INTO Mentee_Tutor_Teacher_Program(username, tutor_teacher_program1, tutor_teacher_program2,
				tutor_teacher_program3, tutor_teacher_program4, tutor_teacher_program5, tutor_teacher_program6) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s',' %s')", $user, $tutor_teacher_program1, $tutor_teacher_program2, $tutor_teacher_program3, 
			$tutor_teacher_program4, $tutor_teacher_program5, $tutor_teacher_program6);
			$ttProgResult = getDBRegInserted($ttProgQuery);
		}

		updateMaxMenteesPerMentor();

		header("Content-type: application/json");
		// echo json_encode($uresult);
		// echo json_encode($mresult);
	}

	function updateMaxMenteesPerMentor() {
		include_once("mentor_maximum.php");

		$currentValue = retrieveMaxMenteesPerMentor();
		$minValue = calcMinMaxMenteesPerMentor();

		if ($currentValue < $minValue) {
			setMaxMenteesPerMentor($minValue);
		}
	}

	function getMenteeMatch() {
		global $_USER;
		$dbQuery = sprintf("SELECT mentor_user FROM Matches WHERE mentee_user = '%s'", $_USER['uid']); // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		$result = getDBResultsArray($dbQuery);
		echo json_encode($result);
	}

	function chooseMentor() {
		echo var_dump($_POST);
		global $_USER;
		// $dbQuery = sprintf("INSERT INTO Matches FROM Mentee WHERE username = '%s'",
		// 										$_USER['uid']);
		$dbQuery = sprintf("INSERT INTO Matches (mentee_user, mentor_user)
					VALUES ('%s', '%s')", $_USER['uid'], $_POST['mentor']);
		
		$result = getDBRegInserted($dbQuery);
		echo json_encode($_POST);
	}



	 function getMentor($mentor) {
		$user = $mentor;

		$dbQuery = sprintf("SELECT *
							FROM USER LEFT JOIN Mentor ON USER.username = Mentor.username 
							LEFT JOIN Mentor_Breadth_Track ON USER.username = Mentor_Breadth_Track.username
							LEFT JOIN Mentor_BME_Organization ON USER.username = Mentor_BME_Organization.username
							LEFT JOIN Mentor_Tutor_Teacher_Program ON USER.username = Mentor_Tutor_Teacher_Program.username
							LEFT JOIN Mentor_BME_Academic_Experience ON USER.username = Mentor_BME_Academic_Experience.username
							LEFT JOIN Mentor_International_Experience ON USER.username = Mentor_International_Experience.username
							LEFT JOIN Mentor_Career_Dev_Program ON USER.username = Mentor_Career_Dev_Program.username
							LEFT JOIN Mentor_Honors_Program ON USER.username = Mentor_Honors_Program.username
							LEFT JOIN Ethnicity ON USER.username = Ethnicity.username
							LEFT JOIN Mentee_Mentor_Organization ON USER.username = Mentee_Mentor_Organization.username
							LEFT JOIN Matches ON USER.username = Matches.mentor_user
							LEFT JOIN Other_Organization ON USER.username = Other_Organization.username
							WHERE USER.username = '%s'", $user); // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		
		$result = getDBResultsArray($dbQuery);
		// $checkMentee = sprintf("SELECT first_name, id, mentor_user FROM User, Mentee WHERE User.username = '%s' AND Mentee.username = '%s'", $user, $user);
		// $isMentee = getDBResultsArray($checkMentee);
		echo json_encode($result);
	 }

	 function listMentors() {
		$dbQuery = "SELECT * FROM USER JOIN Mentor ON USER.username = Mentor.username JOIN Mentor_Breadth_Track ON Mentor_Breadth_Track.username = Mentor.username JOIN Mentor_BME_Organization ON Mentor_BME_Organization.username = Mentor.username JOIN Mentor_Tutor_Teacher_Program ON Mentor_Tutor_Teacher_Program.username = Mentor.username JOIN Mentor_BME_Academic_Experience ON Mentor_BME_Academic_Experience.username = Mentor.username JOIN Mentor_International_Experience ON Mentor_International_Experience.username = Mentor.username JOIN Mentor_Career_Dev_Program ON Mentor_Career_Dev_Program.username = Mentor.username WHERE Mentor.username = USER.username AND (SELECT COUNT(*) FROM Matches WHERE Mentor.username = mentor_user) < (SELECT settingValue FROM GlobalSettings where settingName = 'MaxMenteesPerMentor')"; // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		$result = getDBResultsArray($dbQuery);
		echo json_encode($result);
	}

	 function listUnapprovedMentors() {
		$dbQuery = "SELECT * FROM USER
													JOIN Mentor
														ON  USER.username = Mentor.username
													JOIN Mentor_Breadth_Track
														ON Mentor_Breadth_Track.username = Mentor.username
													JOIN Mentor_BME_Organization
														ON Mentor_BME_Organization.username = Mentor.username
													JOIN Mentor_Tutor_Teacher_Program
														ON Mentor_Tutor_Teacher_Program.username = Mentor.username
													JOIN Mentor_BME_Academic_Experience
														ON Mentor_BME_Academic_Experience.username = Mentor.username
													JOIN Mentor_International_Experience
														ON Mentor_International_Experience.username = Mentor.username
													JOIN Mentor_Career_Dev_Program
														ON Mentor_Career_Dev_Program.username = Mentor.username
													WHERE Mentor.approved = 0"; // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		$result = getDBResultsArray($dbQuery);
		echo json_encode($result);
	}

	 function listApprovedMentors() {
		$dbQuery = "SELECT * FROM USER
													JOIN Mentor
														ON  USER.username = Mentor.username
													JOIN Mentor_Breadth_Track
														ON Mentor_Breadth_Track.username = Mentor.username
													JOIN Mentor_BME_Organization
														ON Mentor_BME_Organization.username = Mentor.username
													JOIN Mentor_Tutor_Teacher_Program
														ON Mentor_Tutor_Teacher_Program.username = Mentor.username
													JOIN Mentor_BME_Academic_Experience
														ON Mentor_BME_Academic_Experience.username = Mentor.username
													JOIN Mentor_International_Experience
														ON Mentor_International_Experience.username = Mentor.username
													JOIN Mentor_Career_Dev_Program
														ON Mentor_Career_Dev_Program.username = Mentor.username
													WHERE Mentor.approved = 1"; // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		$result = getDBResultsArray($dbQuery);
		echo json_encode($result);
	}

	function approveMentor($mentors) {
		foreach ($mentors as $mentor) {
			$mentor = mysql_real_escape_string($mentor);
			$dbQuery = sprintf("UPDATE Mentor SET approved = 1 WHERE Mentor.username = '%s'", $mentor);
			$result = getDBResultsArray($dbQuery);
			echo json_encode($result);
		}
	}

	function addMentorLoop($mentor) {
		echo "addMEntor in PHP \n";
		global $_USER;	
		$user = $mentor['username'];
		$fname = mysql_real_escape_string($mentor['first_name']);//$data->fname);
		$lname = mysql_real_escape_string($mentor['last_name']);
		$alias = mysql_real_escape_string($mentor['alias']);
		$phone = mysql_real_escape_string($mentor['phone']);
		$email = mysql_real_escape_string($mentor['email']);
		$pref_communication = mysql_real_escape_string($mentor['pref_communication']);
		$gender = mysql_real_escape_string($mentor['gender']);
		$depth_focus = mysql_real_escape_string($mentor['depth_focus']);
		$depth_focus_other = mysql_real_escape_string($mentor['depth_focus_other']); 
		$live_before_tech = mysql_real_escape_string($mentor['live_before_tech']);
		$live_on_campus = $mentor['live_on_campus']; //is number 0 or 1 posting?
		$first_gen_college_student = $mentor['first_gen_college_student'];
		$transfer_from_outside = $mentor['transfer_from_outside'];
		$institution_name = mysql_real_escape_string($mentor['institution_name']);
		$transfer_from_within = $mentor['transfer_from_within'];
		$prev_major = mysql_real_escape_string($mentor['prev_major']);
		$international_student = $mentor['international_student'];
		$home_country =  mysql_real_escape_string($mentor['home_country']);
		$expec_graduation = mysql_real_escape_string($mentor['expec_graduation']);
		$other_major =  mysql_real_escape_string($mentor['other_major']);
	
		$ethnicity1 = null;
		$ethnicity2 = null; 
		$ethnicity3 = null;
		$ethnicity4 = null;
		$ethnicity5 - null;
		$ethnicity = $mentor['ethnicity'];
		for ($i=1; $i <= count($ethnicity) ; $i++) {
			${"ethnicity" . $i}  = $ethnicity[$i-1]['name'];
		}

		$honor_program1 = null;
		$honor_program2 = null;
		$honor_program3 = null;
		$hProgs = $mentor['honor_program'];
		for ($i=1; $i <= count($hProgs); $i++) {
			${"honor_program" . $i}  = $hProgs[$i-1]['name']; 
		}		

		$undergrad_research = $mentor['undergrad_research'];
		if ($mentor['undergrad_research']) {
			$undergrad_research_desc = $mentor['undergrad_research_desc'];
		} else {
			$undergrad_research_desc = null;
		}

		if ($mentor['other_organization1']) {
			$other_organization1 = $mentor['other_organization1'];
		} else {
			$other_organization1 = null;
		}
		if ($mentor['other_organization2']) {
			$other_organization2 = $mentor['other_organization2'];
		} else {
			$other_organization2 = null;
		}
		if ($mentor['other_organization3']) {
			$other_organization3 = $mentor['other_organization3'];
		} else {
			$other_organization3 = null;
		}

		$bme_org1 = null;
		$bme_org2 = null;
		$bme_org3 = null;
		$bme_org4 = null;
		$bme_org5 = null;
		$bme_org6 = null;
		$bme_org7 = null;
		//echo var_dump($mentor['bme_organization']);
		$bmeOrgs = $mentor['bme_organization'];
		for ($i=1; $i <= count($bmeOrgs); $i++) {
			//echo $bmeOrgs[$i-1]['name'];
			${"bme_org" . $i}  = $bmeOrgs[$i-1]['name']; //Json of all the organizations $mentor['bme_organization']
		}
		if ($mentor['bme_org_other']) {
			$bme_org_other = mysql_real_escape_string($mentor['bme_org_other']);
		} else {
			$bme_org_other = null;
		}

		$mm_org1 = null;
		$mm_org2 = null;
		$mm_org3 = null;
		$mm_org4 = null;
		$mm_org5 = null;
		$mmOrgs = $mentor['mm_org'];
		for ($i=1; $i <= count($mmOrgs); $i++) {
			${"mm_org" . $i}  = $mmOrgs[$i-1]['name']; //Json of all the organizations $mentor['bme_organization']
		} 
		if ($mentor['mm_org_other']) {
			$mm_org_other = mysql_real_escape_string($mentor['mm_org_other']);
		} else {
			$mm_org_other = null;
		}

		$tutor_teacher_program1 = null;
		$tutor_teacher_program2 = null;
		$tutor_teacher_program3 = null;
		$tutor_teacher_program4 = null;
		$tutor_teacher_program5 = null;
		$tutor_teacher_program6 = null;
		$ttProg = $mentor['tutor_teacher_program'];
		for ($i=1; $i <= count($ttProg); $i++) {
			${"tutor_teacher_program" . $i}  = $ttProg[$i-1]['name']; 
		}
		if ($mentor['tutor_teacher_program_other']) {
			$tutor_teacher_program_other = mysql_real_escape_string($mentor['tutor_teacher_program_other']);
		} else {
			$tutor_teacher_program_other = null;
		}

		$bme_academ_exp1 = null;
		$bme_academ_exp2 = null;
		$bme_academ_exp3 = null;
		$bme_academ_exp4 = null;
		$bmeExp = $mentor['bme_academ_exp'];
		for ($i=1; $i <= count($bmeExp); $i++) {
			${"bme_academ_exp" . $i}  = $bmeExp[$i-1]['name']; 
		}
		if ($mentor['bme_academ_exp_other']) {
			$bme_academ_exp_other = mysql_real_escape_string($mentor['bme_academ_exp_other']);
		} else {
			$bme_academ_exp_other = null;
		}

		$international_experience1 = null;
		$international_experience2 = null;
		$international_experience3 = null;
		$international_experience4 = null;
		$international_experience5 = null;
		$internatExp = $mentor['international_experience'];
		for ($i=1; $i <= count($internatExp); $i++) {
			${"international_experience" . $i}  = $internatExp[$i-1]['name']; 
		}
		if ($mentor['international_experience_other']) {
			$international_experience_other = mysql_real_escape_string($mentor['international_experience_other']);
		} else {
			$international_experience_other = null;
		}

		$career_dev_program1 = null;
		$career_dev_program2 = null;
		$career_dev_program3 = null;
		$carDevProg = $mentor['career_dev_program']; 
		for ($i=1; $i <= count($carDevProg); $i++) {
			${"career_dev_program" . $i}  = $carDevProg[$i-1]['name']; 
		}
		if ($mentor['career_dev_program_other']) {
			$career_dev_program_other = mysql_real_escape_string($mentor['career_dev_program_other']);
		} else {
			$career_dev_program_other = null;
		}

		$post_grad_plan = mysql_real_escape_string($mentor['post_grad_plan']);
		if ($mentor['post_grad_plan_desc']) {
			$post_grad_plan_desc = mysql_real_escape_string($mentor['post_grad_plan_desc']);
		} else {
			$post_grad_plan_desc = null;
		}

		$personal_hobby = mysql_real_escape_string($mentor['personal_hobby']);

		$userQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s','%s','%s','%s')", $user, $lname, $fname, $phone, $email, $pref_communication);
		$uResult = getDBRegInserted($userQuery);


		$mentorQuery = sprintf("INSERT INTO Mentor (username, alias, gender, depth_focus, depth_focus_other,
			live_before_tech, live_on_campus, first_gen_college_student, transfer_from_outside, institution_name,
			transfer_from_within, prev_major, international_student, home_country, expec_graduation, other_major, 
			undergrad_research, undergrad_research_desc, post_grad_plan, post_grad_plan_desc, personal_hobby) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%u', '%u', '%u', '%u', '%s', '%u', '%s', '%u', '%s', '%s', '%s', 
				'%u', '%s', '%s', '%s', '%s')", 
			$user, $alias, $gender, $depth_focus, $depth_focus_other,
			$live_before_tech, $live_on_campus, $first_gen_college_student, $transfer_from_outside, $institution_name, 
			$transfer_from_within, $prev_major, $international_student, $home_country, $expec_graduation, $other_major,
			$undergrad_research, $undergrad_research_desc, $post_grad_plan, $post_grad_plan_desc, $personal_hobby);
		$mResult = getDBRegInserted($mentorQuery);

		$bTrack = $mentor['breadth_track'];
		//echo var_dump($bTrack);
		foreach ($bTrack as $key => $value) {
			$breadth_track = $value['name'];
			//echo $value['name'];
			$breadth_track_desc = $value['desc'];
			$bTrackQuery = sprintf("INSERT INTO Mentor_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
			$user, $breadth_track, $breadth_track_desc);
			$bTrackResult = getDBRegInserted($bTrackQuery);
		}

		if ($mentor['ethnicity']) {
		$ethQuery = sprintf("INSERT INTO Ethnicity(username, ethnicity1, ethnicity2, ethnicity3, ethnicity4, ethnicity5) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $user, $ethnicity1, $ethnicity2, $ethnicity3, $ethnicity4, $ethnicity5);
		$eResult = getDBRegInserted($ethQuery);
		}

		if ($mentor['honor_program']) {
		$honorProgQuery = sprintf("INSERT INTO Mentor_Honors_Program(username, program1, program2, program3) 
			VALUES ('%s', '%s', '%s', '%s')", $user, $honor_program1, $honor_program2, $honor_program3);
		$hpResult = getDBRegInserted($honorProgQuery);
		}

		if ($mentor['other_organization1']) {
			$otherOrgQuery = sprintf("INSERT INTO Other_Organization(username, organization1, organization2, organization3) 
				VALUES ('%s', '%s', '%s', '%s')", $user, $other_organization1, $other_organization2, $other_organization3);
			$otherOrgResult = getDBRegInserted($otherOrgQuery);
		}

		if ($mentor['bme_organization']) {
		$bmeOrgQuery = sprintf("INSERT INTO Mentor_BME_Organization(username, bme_org1, bme_org2, bme_org3,
			bme_org4, bme_org5, bme_org6, bme_org7, bme_org_other) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s', '%s')",
			$user, $bme_org1, $bme_org2, $bme_org3, $bme_org4, $bme_org5, $bme_org6, $bme_org7, $bme_org_other);
		$bmeOrgResult = getDBRegInserted($bmeOrgQuery);
		}

		if ($mentor['mm_org']) {
		$mmOrgQuery = sprintf("INSERT INTO Mentee_Mentor_Organization(username, mm_org1, mm_org2, mm_org3, mm_org4, mm_org5, mm_org_other) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $mm_org1, $mm_org2, $mm_org3, $mm_org4, $mm_org5, $mm_org_other);
		$mmResult = getDBRegInserted($mmOrgQuery);
		}

		if ($mentor['tutor_teacher_program']) {
		$ttProgQuery = sprintf("INSERT INTO Mentor_Tutor_Teacher_Program(username, tutor_teacher_program1, tutor_teacher_program2, 
			tutor_teacher_program3, tutor_teacher_program4, tutor_teacher_program5, tutor_teacher_program6, tutor_teacher_program_other)
		 VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $tutor_teacher_program1, $tutor_teacher_program2, 
		 $tutor_teacher_program3, $tutor_teacher_program4, $tutor_teacher_program5, $tutor_teacher_program6, $tutor_teacher_program_other);
		$ttProgResult = getDBRegInserted($ttProgQuery);
		}

		if ($mentor['bme_academ_exp']) {
		$bmeQuery = sprintf("INSERT INTO Mentor_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
			bme_academ_exp3, bme_academ_exp4, bme_academ_exp_other) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
		$user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4, $bme_academ_exp_other);
		$bmeResult = getDBRegInserted($bmeQuery);
		}

		if ($mentor['international_experience']) {
		$interQuery = sprintf("INSERT INTO Mentor_International_Experience(username, international_experience1, international_experience2, 
			international_experience3, international_experience4, international_experience5, international_experience_other)
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
		$international_experience3, $international_experience4, $international_experience5, $international_experience_other);
		$interResults = getDBRegInserted($interQuery);
		}

		if ($mentor['career_dev_program']) {
		$careerQuery = sprintf("INSERT INTO Mentor_Career_Dev_Program(username, career_dev_program1,
			career_dev_program2, career_dev_program3, career_dev_program_other) VALUES ('%s', '%s', '%s','%s', '%s')",
			 $user, $career_dev_program1, $career_dev_program2, $career_dev_program3, $career_dev_program_other);
		$careerResults = getDBRegInserted($careerQuery);
		}

		// //header("Content-type: application/json");
		// // print_r($json);
		// echo json_encode($uresult+$mresult);
		
	}//end addMentor

	function addMentor() {
		echo "addMEntor in PHP \n";
		global $_USER;	
		$user = $_USER['uid'];
		$fname = mysql_real_escape_string($_POST['fname']);//$data->fname);
		$lname = mysql_real_escape_string($_POST['lname']);
		$phone = mysql_real_escape_string($_POST['phone']);
		$email = mysql_real_escape_string($_POST['email']);
		$pref_communication = mysql_real_escape_string($_POST['pref_communication']);
		$gender = mysql_real_escape_string($_POST['gender']);
		$depth_focus = mysql_real_escape_string($_POST['dfocus']);
		$depth_focus_other = mysql_real_escape_string($_POST['dfocusother']); 
		$live_before_tech = mysql_real_escape_string($_POST['live_before_tech']);
		$live_on_campus = $_POST['live_on_campus']; //is number 0 or 1 posting?
		$first_gen_college_student = $_POST['first_gen_college_student'];
		$transfer_from_outside = $_POST['transfer_from_outside'];
		$institution_name = mysql_real_escape_string($_POST['institution_name']);
		$transfer_from_within = $_POST['transfer_from_within'];
		$prev_major = mysql_real_escape_string($_POST['prev_major']);
		$international_student = $_POST['international_student'];
		$home_country =  mysql_real_escape_string($_POST['home_country']);
		$expec_graduation = mysql_real_escape_string($_POST['expec_graduation']);
		$other_major =  mysql_real_escape_string($_POST['other_major']);
	
		$ethnicity1 = null;
		$ethnicity2 = null; 
		$ethnicity3 = null;
		$ethnicity4 = null;
		$ethnicity5 - null;
		$ethnicity = $_POST['ethnicity'];
		for ($i=1; $i <= count($ethnicity) ; $i++) {
			${"ethnicity" . $i}  = $ethnicity[$i-1]['name'];
		}

		$honor_program1 = null;
		$honor_program2 = null;
		$honor_program3 = null;
		$hProgs = $_POST['honor_program'];
		for ($i=1; $i <= count($hProgs); $i++) {
			${"honor_program" . $i}  = $hProgs[$i-1]['name']; 
		}		

		$undergrad_research = $_POST['undergrad_research'];
		if ($_POST['undergrad_research']) {
			$undergrad_research_desc = $_POST['undergrad_research_desc'];
		} else {
			$undergrad_research_desc = null;
		}

		if ($_POST['other_organization1']) {
			$other_organization1 = $_POST['other_organization1'];
		} else {
			$other_organization1 = null;
		}
		if ($_POST['other_organization2']) {
			$other_organization2 = $_POST['other_organization2'];
		} else {
			$other_organization2 = null;
		}
		if ($_POST['other_organization3']) {
			$other_organization3 = $_POST['other_organization3'];
		} else {
			$other_organization3 = null;
		}

		$bme_org1 = null;
		$bme_org2 = null;
		$bme_org3 = null;
		$bme_org4 = null;
		$bme_org5 = null;
		$bme_org6 = null;
		$bme_org7 = null;
		$bmeOrgs = $_POST['bme_organization'];
		for ($i=1; $i <= count($bmeOrgs); $i++) {
			${"bme_org" . $i}  = $bmeOrgs[$i-1]['name']; //Json of all the organizations $_POST['bme_organization']
		}
		if ($_POST['bme_org_other']) {
			$bme_org_other = mysql_real_escape_string($_POST['bme_org_other']);
		} else {
			$bme_org_other = null;
		}

		$mm_org1 = null;
		$mm_org2 = null;
		$mm_org3 = null;
		$mm_org4 = null;
		$mm_org5 = null;
		$mmOrgs = $_POST['mm_org'];
		for ($i=1; $i <= count($mmOrgs); $i++) {
			${"mm_org" . $i}  = $mmOrgs[$i-1]['name']; //Json of all the organizations $_POST['bme_organization']
		} 
		if ($_POST['mm_org_other']) {
			$mm_org_other = mysql_real_escape_string($_POST['mm_org_other']);
		} else {
			$mm_org_other = null;
		}

		$tutor_teacher_program1 = null;
		$tutor_teacher_program2 = null;
		$tutor_teacher_program3 = null;
		$tutor_teacher_program4 = null;
		$tutor_teacher_program5 = null;
		$tutor_teacher_program6 = null;
		$ttProg = $_POST['tutor_teacher_program'];
		for ($i=1; $i <= count($ttProg); $i++) {
			${"tutor_teacher_program" . $i}  = $ttProg[$i-1]['name']; 
		}
		if ($_POST['tutor_teacher_program_other']) {
			$tutor_teacher_program_other = mysql_real_escape_string($_POST['tutor_teacher_program_other']);
		} else {
			$tutor_teacher_program_other = null;
		}

		$bme_academ_exp1 = null;
		$bme_academ_exp2 = null;
		$bme_academ_exp3 = null;
		$bme_academ_exp4 = null;
		$bmeExp = $_POST['bme_academ_exp'];
		for ($i=1; $i <= count($bmeExp); $i++) {
			${"bme_academ_exp" . $i}  = $bmeExp[$i-1]['name']; 
		}
		if ($_POST['bme_academ_exp_other']) {
			$bme_academ_exp_other = mysql_real_escape_string($_POST['bme_academ_exp_other']);
		} else {
			$bme_academ_exp_other = null;
		}

		$international_experience1 = null;
		$international_experience2 = null;
		$international_experience3 = null;
		$international_experience4 = null;
		$international_experience5 = null;
		$internatExp = $_POST['international_experience'];
		for ($i=1; $i <= count($internatExp); $i++) {
			${"international_experience" . $i}  = $internatExp[$i-1]['name']; 
		}
		if ($_POST['international_experience_other']) {
			$international_experience_other = mysql_real_escape_string($_POST['international_experience_other']);
		} else {
			$international_experience_other = null;
		}

		$career_dev_program1 = null;
		$career_dev_program2 = null;
		$career_dev_program3 = null;
		$carDevProg = $_POST['career_dev_program']; 
		for ($i=1; $i <= count($carDevProg); $i++) {
			${"career_dev_program" . $i}  = $carDevProg[$i-1]['name']; 
		}
		if ($_POST['career_dev_program_other']) {
			$career_dev_program_other = mysql_real_escape_string($_POST['career_dev_program_other']);
		} else {
			$career_dev_program_other = null;
		}

		$post_grad_plan = mysql_real_escape_string($_POST['post_grad_plan']);
		if ($_POST['post_grad_plan_desc']) {
			$post_grad_plan_desc = mysql_real_escape_string($_POST['post_grad_plan_desc']);
		} else {
			$post_grad_plan_desc = null;
		}

		$personal_hobby = mysql_real_escape_string($_POST['personal_hobby']);

		$userQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s','%s','%s','%s')", $user, $lname, $fname, $phone, $email, $pref_communication);
		$uResult = getDBRegInserted($userQuery);


		$mentorQuery = sprintf("INSERT INTO Mentor (username, gender, depth_focus, depth_focus_other,
			live_before_tech, live_on_campus, first_gen_college_student, transfer_from_outside, institution_name,
			transfer_from_within, prev_major, international_student, home_country, expec_graduation, other_major, 
			undergrad_research, undergrad_research_desc, post_grad_plan, post_grad_plan_desc, personal_hobby) 
			VALUES ('%s', '%s', '%s', '%s', '%u', '%u', '%u', '%u', '%s', '%u', '%s', '%u', '%s', '%s', '%s', 
				'%u', '%s', '%s', '%s', '%s')", 
			$user, $gender, $depth_focus, $depth_focus_other,
			$live_before_tech, $live_on_campus, $first_gen_college_student, $transfer_from_outside, $institution_name, 
			$transfer_from_within, $prev_major, $international_student, $home_country, $expec_graduation, $other_major,
			$undergrad_research, $undergrad_research_desc, $post_grad_plan, $post_grad_plan_desc, $personal_hobby);
		$mResult = getDBRegInserted($mentorQuery);

		$bTrack = $_POST['breadth_track'];
		foreach ($bTrack as $key => $value) {
			$breadth_track = $value['name'];
			$breadth_track_desc = $value['desc'];
			$bTrackQuery = sprintf("INSERT INTO Mentor_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
			$user, $breadth_track, $breadth_track_desc);
			$bTrackResult = getDBRegInserted($bTrackQuery);
		}

		if ($_POST['ethnicity']) {
		$ethQuery = sprintf("INSERT INTO Ethnicity(username, ethnicity1, ethnicity2, ethnicity3, ethnicity4, ethnicity5) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $user, $ethnicity1, $ethnicity2, $ethnicity3, $ethnicity4, $ethnicity5);
		$eResult = getDBRegInserted($ethQuery);
		}

		if ($_POST['honor_program']) {
		$honorProgQuery = sprintf("INSERT INTO Mentor_Honors_Program(username, program1, program2, program3) 
			VALUES ('%s', '%s', '%s', '%s')", $user, $honor_program1, $honor_program2, $honor_program3);
		$hpResult = getDBRegInserted($honorProgQuery);
		}

		if ($_POST['other_organization1']) {
			$otherOrgQuery = sprintf("INSERT INTO Other_Organization(username, organization1, organization2, organization3) 
				VALUES ('%s', '%s', '%s', '%s')", $user, $other_organization1, $other_organization2, $other_organization3);
			$otherOrgResult = getDBRegInserted($otherOrgQuery);
		}

		if ($_POST['bme_organization']) {
		$bmeOrgQuery = sprintf("INSERT INTO Mentor_BME_Organization(username, bme_org1, bme_org2, bme_org3,
			bme_org4, bme_org5, bme_org6, bme_org7, bme_org_other) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s', '%s')",
			$user, $bme_org1, $bme_org2, $bme_org3, $bme_org4, $bme_org5, $bme_org6, $bme_org7, $bme_org_other);
		$bmeOrgResult = getDBRegInserted($bmeOrgQuery);
		}

		if ($_POST['mm_org']) {
		$mmOrgQuery = sprintf("INSERT INTO Mentee_Mentor_Organization(username, mm_org1, mm_org2, mm_org3, mm_org4, mm_org5, mm_org_other) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $mm_org1, $mm_org2, $mm_org3, $mm_org4, $mm_org5, $mm_org_other);
		$mmResult = getDBRegInserted($mmOrgQuery);
		}

		if ($_POST['tutor_teacher_program']) {
		$ttProgQuery = sprintf("INSERT INTO Mentor_Tutor_Teacher_Program(username, tutor_teacher_program1, tutor_teacher_program2, 
			tutor_teacher_program3, tutor_teacher_program4, tutor_teacher_program5, tutor_teacher_program6, tutor_teacher_program_other)
		 VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $tutor_teacher_program1, $tutor_teacher_program2, 
		 $tutor_teacher_program3, $tutor_teacher_program4, $tutor_teacher_program5, $tutor_teacher_program6, $tutor_teacher_program_other);
		$ttProgResult = getDBRegInserted($ttProgQuery);
		}

		if ($_POST['bme_academ_exp']) {
		$bmeQuery = sprintf("INSERT INTO Mentor_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
			bme_academ_exp3, bme_academ_exp4, bme_academ_exp_other) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
		$user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4, $bme_academ_exp_other);
		$bmeResult = getDBRegInserted($bmeQuery);
		}

		if ($_POST['international_experience']) {
		$interQuery = sprintf("INSERT INTO Mentor_International_Experience(username, international_experience1, international_experience2, 
			international_experience3, international_experience4, international_experience5, international_experience_other)
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
		$international_experience3, $international_experience4, $international_experience5, $international_experience_other);
		$interResults = getDBRegInserted($interQuery);
		}

		if ($_POST['career_dev_program']) {
		$careerQuery = sprintf("INSERT INTO Mentor_Career_Dev_Program(username, career_dev_program1,
			career_dev_program2, career_dev_program3, career_dev_program_other) VALUES ('%s', '%s', '%s','%s', '%s')",
			 $user, $career_dev_program1, $career_dev_program2, $career_dev_program3, $career_dev_program_other);
		$careerResults = getDBRegInserted($careerQuery);
		}

		// //header("Content-type: application/json");
		// // print_r($json);
		// echo json_encode($uresult+$mresult);
		
	}//end addMentor

	function listAliasNames($alias) {
		$countHasName = sprintf("SELECT username FROM Mentor WHERE Mentor.alias = '%s'", $alias);
		$nameResult = getDBResultRecord($countHasName);
		if ($nameResult) {
			echo "name already exists";
		} else {
			echo "name does not already exist, ok to show this alias";
		}
		header("Content-type: application/json");
		echo json_encode($nameResult);
	}

	function inputAliasName($aliasName) {
		global $_USER;
		$user = $_USER['uid'];

		$query = sprintf("UPDATE Mentor SET alias = '%s' WHERE username = '%s'", $aliasName, $user);
		$queryRestult = getDBResultInserted($query);
		header("Content-type: application/json");
		echo json_encode($queryResult);
	}

	function genFauxUsers($form) {
		global $_USER;

		//$form = json_decode($form);
		$count = 0;
		foreach($form as $currentUser) {
			$count++;
			$dbQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
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

	function deleteMentors() {
		global $_USER;

		$dbQueryMentor = sprintf("DELETE FROM USER WHERE username IN (SELECT username FROM Mentor)");

		$result = deleteDBEntries($dbQueryMentor);
		//header("Content-type: application/json");
		echo "deleted";
		//echo json_encode();
	}

	function genFauxMentors() {

		//$form = json_decode($form);
		$count = 0;
		foreach($_POST['mentors'] as $cu) {
			//echo var_dump($cu);
			addMentorLoop($cu);
		}
		//echo var_dump($_POST);
		header("Content-type: application/json");
		//echo json_encode($_POST);
	}

	// function genFauxMentors() {
	// 	global $_USER;

	// 	//$form = json_decode($form);
	// 	$count = 0;
	// 	foreach($_POST['mentors'] as $cu) {
	// 		$count++;
	// 		$dbQuery = sprintf("INSERT INTO USER (username, last_name, first_name, phone_num, email, pref_communication)
	// 												VALUES ('%s', '%s', '%s', '%u', '%s', '%s')",
	// 												$cu['username'], $cu['first_name'], $cu['last_name'], 
	// 												$cu['phone_number'], $cu['email'], $cu['pref_communication']);
	// 		$result = getDBRegInserted($dbQuery);

	// 		$dbQuery = sprintf("INSERT INTO Mentor (username, alias, opt_in, depth_focus, post_grad_plan, expec_graduation,
	// 																					 	transfer_from_within, transfer_from_outside, international_student,
	// 																					 	first_gen_college_student, live_before_tech, live_on_campus,
	// 																					 	undergrad_research, home_country, gender)
	// 												VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
	// 												$cu['username'], $cu['alias'], $cu['opt_in'], $cu['depth_focus'], $cu['post_grad_plan'], 
	// 												$cu['expec_graduation'], $cu['transfer_from_within,'], $cu['transfer_from_outside'],
	// 												$cu['international_student'], $cu['first_gen_college_student'], $cu['live_before_tech'], 
	// 												$cu['live_on_campus'], $cu['undergrad_research'], $cu['home_country'], $cu['gender']);
	// 		$result = getDBRegInserted($dbQuery);
	// 	}
	// 	//echo var_dump($_POST);
	// 	header("Content-type: application/json");
	// 	echo json_encode($_POST);
	// }

	//==================================
	// RequestPeriod Code
	//==================================
	/**
	 * Function that determines whether or not the given request period is currently open
	 */
	function getRequestPeriodStatus($requestPeriod){
		$dbQuery = sprintf("SELECT isOpen FROM RequestPeriods WHERE RequestPeriod = '%s'",
			mysql_real_escape_string($requestPeriod));
		$result=getDBResultsArray($dbQuery)[0];
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	/**
	 * Function that determines whether or not the default request period is currently open
	 */
	function getDefaultPeriodStatus(){
		$defaultPeriod = "DefaultRequestPeriod";
		getRequestPeriodStatus($defaultPeriod);
	}
	
	/**
	 * Opens a given request period
	 */
	function openRequestPeriod($requestPeriod){
		$dbQuery = sprintf("UPDATE RequestPeriods SET isOpen = 1 WHERE RequestPeriod = '%s'",
			mysql_real_escape_string($requestPeriod));
		$result=getDBRegInserted($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	/**
	 * Closes a given request period
	 */
	function closeRequestPeriod($requestPeriod){
		$dbQuery = sprintf("UPDATE RequestPeriods SET isOpen = 0 WHERE RequestPeriod = '%s'",
			mysql_real_escape_string($requestPeriod));
		$result=getDBRegInserted($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function putDefaultPeriodStatus($newStatus) {
		if ($newStatus == 0) {
			closeDefaultRequestPeriod();
		} else {
			openDefaultRequestPeriod();
		}
	}
	
	/**
	 * Opens the default request period
	 */
	function openDefaultRequestPeriod(){
		$defaultPeriod = "DefaultRequestPeriod";
		openRequestPeriod($defaultPeriod);
	}
	
	/**
	 * Closes the default request period
	 */
	function closeDefaultRequestPeriod(){
		$defaultPeriod = "DefaultRequestPeriod";
		closeRequestPeriod($defaultPeriod);
	}

	function getWishlistContents() {
		global $_USER;
		$dbQueryWishlist = sprintf("SELECT * FROM Wishlist
		JOIN Mentor ON  Wishlist.mentor = Mentor.username
		JOIN USER ON Mentor.username = USER.username
		JOIN Mentor_Breadth_Track ON Mentor_Breadth_Track.username = Mentor.username
		JOIN Mentor_BME_Organization ON Mentor_BME_Organization.username = Mentor.username
		JOIN Mentor_Tutor_Teacher_Program ON Mentor_Tutor_Teacher_Program.username = Mentor.username
		JOIN Mentor_BME_Academic_Experience ON Mentor_BME_Academic_Experience.username = Mentor.username
		JOIN Mentor_International_Experience ON Mentor_International_Experience.username = Mentor.username
		JOIN Mentor_Career_Dev_Program ON Mentor_Career_Dev_Program.username = Mentor.username
		WHERE Wishlist.mentee = '%s' AND (SELECT COUNT(*) FROM Matches WHERE Wishlist.mentor = mentor_user) < (SELECT settingValue FROM GlobalSettings where settingName = 'MaxMenteesPerMentor')", $_USER['uid']);
		$result=getDBResultsArray($dbQueryWishlist);
		header("Content-type: application/json");
		echo json_encode($result);
	}

	/**
	 * Adds a mentor to the currently logged in user's wishlist.
	 */
	function addWishlistMentor($username) {
		global $_USER;
		$dbQueryWishlist = sprintf("INSERT INTO Wishlist (mentee, mentor) VALUES ('%s', '%s')", $_USER['uid'], $username);
		$result = getDBRegInserted($dbQueryWishlist);
		echo "added";
	}

	/**
	 * Removes a mentor from the currently logged in user's wishlist.
	 */
	function removeWishlistMentor($username) {
		global $_USER;
		$dbQueryWishlist = sprintf("DELETE FROM Wishlist WHERE mentee='%s' AND mentor='%s'", $_USER['uid'], $username);
		$result = deleteDBEntries($dbQueryWishlist);
		print($result);
	}
	
	
	function mentorHasSpace($username){
		$countHasName = sprintf("SELECT TRUE FROM Mentor WHERE Mentor.username = '%s'
			AND (SELECT COUNT(*) FROM Matches WHERE username = mentor_user) < (SELECT settingValue 				FROM GlobalSettings where settingName = 'MaxMenteesPerMentor')", $username);
		$result = mysql_num_rows(mysql_query($countHasName));
		header("Content-type: application/json");
		echo json_encode($result == 1);
	}
?>
