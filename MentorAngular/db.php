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
		$dbQuery = sprintf("DELETE FROM Mentee WHERE username = '%s'",
												$_USER['uid']);
		$result = getDBResultsArray($dbQuery);
		$dbQuery = sprintf("DELETE FROM User WHERE username = '%s'",
												$_USER['uid']);
		$result = getDBResultsArray($dbQuery);
		$dbQuery = sprintf("DELETE FROM Matches WHERE mentee_user = '%s'",
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

		$userQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s', '%s','%s','%s')", $user, $lname, $fname,$phone,$email,$pref_comm);
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

		header("Content-type: application/json");
		// echo json_encode($uresult);
		// echo json_encode($mresult);
	}

	function getMenteeMatch() {
		global $_USER;
		$dbQuery = sprintf("SELECT mentor_user FROM Matches WHERE mentee_user = '%s'", $_USER['uid']); // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		$result = getDBResultsArray($dbQuery);
		echo json_encode($result);
	}

	function chooseMentor() {
		global $_USER;
		// $dbQuery = sprintf("INSERT INTO Matches FROM Mentee WHERE username = '%s'",
		// 										$_USER['uid']);
		$dbQuery = sprintf("INSERT INTO Matches (mentee_user, mentor_user)
					VALUES ('%s', '%s')", $_USER['uid'], $_POST['mentor']);
		
		$result = getDBResultsArray($dbQuery);
		echo json_encode($_POST);
	}



	 function getMentor($mentor) {
	// 	global $_USER;

		// $dbQuery = sprintf("SELECT first_name, last_name, alias, 
		// 														email, phone_num, pref_communication, depth_focus, 
		// 														post_grad_plan, expec_graduation, gender,  
		// 														expec_graduation 
		// 														FROM User, Mentor WHERE User.username = '%s' AND Mentor.username = '%s'",
		// 														$mentor, $mentor); // breadth_track, student_year, career_dev_program, future_plans, Mentor_BME_Academic_Experience,
		// $result = getDBResultsArray($dbQuery);

		// $checkMentee = sprintf("SELECT first_name, id, mentor_user FROM User, Mentee WHERE User.username = '%s' AND Mentee.username = '%s'", $user, $user);
		// $isMentee = getDBResultsArray($checkMentee);
	// 	echo json_encode($_REST);
	 }

	function addMentor() {
		echo "addMEntor \n";
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
		$institution_name = mysql_real_escape_string($_POST['$institution_name']);
		$transfer_from_within = $_POST['transfer_from_within'];
		$prev_major = mysql_real_escape_string($_POST['$prev_major']);
		$international_student = $_POST['$international_student'];
		$home_country =  mysql_real_escape_string($_POST['$home_country']);
		$expec_graduation = mysql_real_escape_string($_POST['$expec_graduation']);
		$other_major =  mysql_real_escape_string($_POST['$other_major']);
	
		$ethnicity1 = null;
		$ethnicity2 = null; 
		$ethnicity3 = null;
		$ethnicity4 = null;
		$ethnicity5 - null;
		$ethnicity = $_POST['ethnicity'];
		for ($i=1; $i <= count($ethnicity) ; $i++) {
			${"ethnicity" . $i}  = $ethnicity[$i-1]['name'];
		}

		$program1 = null;
		$program2 = null;
		$program3 = null;
		$hProgs = $_POST['honor_program'];
		for ($i=1; $i <= count($hProgs); $i++) {
			${"honor_program" . $i}  = $hProgs[$i-1]['name']; 
		}		

		$undergrad_research = $_POST['undergrad_research'];
		$undergrad_research_desc = $_POST['$undergrad_research_desc'];

		$other_organization1 = $_POST['other_organization1'];
		$other_organization2 = $_POST['other_organization2'];
		$other_organization3 = $_POST['other_organization3'];

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
		$bme_org_other = mysql_real_escape_string($_POST['$bme_org_other']);

		$mm_org1 = null;
		$mm_org2 = null;
		$mm_org3 = null;
		$mm_org4 = null;
		$mm_org5 = null;
		$mmOrgs = $_POST['mm_org'];
		for ($i=1; $i <= count($mmOrgs); $i++) {
			${"mmm_org" . $i}  = $mmOrgs[$i-1]['name']; //Json of all the organizations $_POST['bme_organization']
		}
		$mm_org_other = mysql_real_escape_string($_POST['$mm_org_other']);

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
		$tutor_teacher_program_other = mysql_real_escape_string($_POST['$tutor_teacher_program_other']);

		$bme_academ_exp1 = null;
		$bme_academ_exp2 = null;
		$bme_academ_exp3 = null;
		$bme_academ_exp4 = null;
		$bmeExp = $_POST['bme_academ_exp'];
		for ($i=1; $i <= count($bmeExp); $i++) {
			${"bme_academ_exp" . $i}  = $bmeExp[$i-1]['name']; 
		}
		$bme_academ_exp_other = mysql_real_escape_string($_POST['$bme_academ_exp_other']);

		$international_experience1 = null;
		$international_experience2 = null;
		$international_experience3 = null;
		$international_experience4 = null;
		$international_experience5 = null;
		$internatExp = $_POST['international_experience'];
		for ($i=1; $i <= count($internatExp); $i++) {
			${"international_experience" . $i}  = $internatExp[$i-1]['name']; 
		}
		$international_experience_other = mysql_real_escape_string($_POST['international_experience_other']);

		$career_dev_program1 = null;
		$career_dev_program2 = null;
		$career_dev_program3 = null;
		$carDevProg = $_POST['career_dev_program']; 
		for ($i=1; $i <= count($carDevProg); $i++) {
			${"career_dev_program" . $i}  = $carDevProg[$i-1]['name']; 
		}
		$career_dev_program_other = mysql_real_escape_string($_POST['career_dev_program_other']);

		$post_grad_plan = mysql_real_escape_string($_POST['post_grad_plan']);
		$post_grad_plan_desc = mysql_real_escape_string($_POST['post_grad_plan_desc']);

		$personal_hobby = mysql_real_escape_string($_POST['personal_hobby']);

		$userQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
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

		$bTracks = $_POST['breadth_track'];
		foreach ($bTrack as $key => $value) {
			$breadth_track = $value['name'];
			$breadth_track_desc = $value['desc'];
			$bTrackQuery = sprintf("INSERT INTO Mentor_Breadth_Track(username, breadth_track, breadth_track_desc) VALUES ('%s', '%s', '%s')",
			$user, $breadth_track, $breadth_track_desc);
			$bTrackResult = getDBRegInserted($bTrackQuery);
		}

		$ethQuery = sprintf("INSERT INTO Ethnicity(username, ethnicity1, ethnicity2, ethnicity3, ethnicity4, ethnicity5) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $user, $ethnicity1, $ethnicity2, $ethnicity3, $ethnicity4, $ethnicity5);
		$eResult = getDBRegInserted($ethQuery);

		$honorProgQuery = sprintf("INSERT INTO Mentor_Honors_Program(username, program1, program2, program3) 
			VALUES ('%s', '%s', '%s', '%s')", $user, $program1, $program2, $program3);
		$hpResult = getDBRegInserted($honorProgQuery);

		$otherOrgQuery = sprintf("INSERT INTO Other_Organization(username, organization1, organization2, organization3) 
			VALUES('%s', '%s', '%s', '%s')", $user, $other_organization1, $other_organization2, $other_organization3);
		$otherOrgResult = getDBRegInserted($otherOrgQuery);

		$bmeOrgQuery = sprintf("INSERT INTO Mentor_BME_Organization(username, bme_org1, bme_org2, bme_org3,
			bme_org4, bme_org5, bme_org6, bme_org7, bme_org_other) VALUES ('%s', '%s', '%s' , '%s', '%s', '%s', '%s', '%s', '%s')",
			$user, $bme_org1, $bme_org2, $bme_org3, $bme_org4, $bme_org5, $bme_org6, $bme_org7, $bme_org_other);
		$bmeOrgResult = getDBRegInserted($bmeOrgQuery);

		$mmOrgQuery = sprintf("INSERT INTO Mentee_Mentor_Organization(username, mm_org1, mm_org2, mm_org3, mm_org4, mm_org5, mm_org_other), 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $mm_org1, $mm_org2, $mm_org3, $mm_org4, $mm_org5, $mm_org_other);
		$mmResult = getDBRegInserted($mmOrgQuery);

		$ttProgQuery = sprintf("INSERT INTO Mentor_Tutor_Teacher_Program(username, tutor_teacher_program1, tutor_teacher_program2, 
			tutor_teacher_program3, tutor_teacher_program4, tutor_teacher_program5, tutor_teacher_program6, tutor_teacher_program_other)
		 VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $tutor_teacher_program1, $tutor_teacher_program2, 
		 $tutor_teacher_program3, $tutor_teacher_program4, $tutor_teacher_program5, $tutor_teacher_program6, $tutor_teacher_program_other);
		$ttProgResult = getDBRegInserted($ttProgQuery);

		$bmeQuery = sprintf("INSERT INTO Mentor_BME_Academic_Experience(username, bme_academ_exp1, bme_academ_exp2,
			bme_academ_exp3, bme_academ_exp4, bme_academ_exp_other) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
		$user, $bme_academ_exp1, $bme_academ_exp2, $bme_academ_exp3, $bme_academ_exp4, $bme_academ_exp_other);
		$bmeResult = getDBRegInserted($bmeQuery);

		$interQuery = sprintf("INSERT INTO Mentor_International_Experience(username, international_experience1, international_experience2, 
			international_experience3, international_experience4, international_experience5, international_experience_other)
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $user, $international_experience1, $international_experience2,
		$international_experience3, $international_experience4, $international_experience5, $international_experience_other);
		$interResults = getDBRegInserted($interQuery);

		$careerQuery = sprintf("INSERT INTO Mentor_Career_Dev_Program(username, career_dev_program1,
			career_dev_program2, career_dev_program3, career_dev_program_other) VALUES ('%s', '%s', 
			'%s','%s','%s')", $user, $career_dev_program1, $career_dev_program2, $career_dev_program3,
		$career_dev_program_other);
		$careerResults = getDBRegInserted($careerQuery);

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