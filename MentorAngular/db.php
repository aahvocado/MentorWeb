<?php
	include 'db_helper.php';
	header("Access-Control-Allow-Origin: *");

	switch($_GET['action']) {
		case 'addMentor' :
		addMentor();
		break;

		case 'getMentor' :
		getMentor();
		break;
	}

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

	 	 header("Content-type: application/json");
	 	 //echo var_dump($userinfo);
	 	 echo json_encode($userInfo);
	}//end welcome

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

	function addMentor() {
		global $_USER;	
		// $data = file_get_contents("php://input");
		$user = $_USER['uid'];
		$fname = mysql_real_escape_string($_POST['fname']);//$data->fname);
		$lname = mysql_real_escape_string($_POST['lname']);
		$phone = mysql_real_escape_string($_POST['phone']);
		$email = mysql_real_escape_string($_POST['email']);
		$pref_comm = mysql_real_escape_string($_POST['pref_comm']);
		$gender = mysql_real_escape_string($_POST['gender']);
		$ethnicity = mysql_real_escape_string($_POST['ethnicity']);
		$depth_focus = mysql_real_escape_string($_POST['dfocus']);
		$depth_focus_desc = mysql_real_escape_string($_POST['dfocusother']);

		
		$userQuery = sprintf("INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('%s', '%s', '%s','%s','%s','%s')", $_USER['uid'], $lname, $fname,$phone,$email,$pref_comm);
		$uresult = getDBRegInserted($userQuery);


		$mentorQuery = sprintf("INSERT INTO Mentor (username, gender, ethnicity, opt_in, depth_focus, depth_focus_desc) 
			VALUES ('%s', '%s', %s', '%s', '%s', '%s')", $user, $gender, $ethnicity, $opt_in, $depth_focus, $depth_focus_desc);
				// $post_grad_plan, $post_grad_plan_desc, $expec_graduation, $transfer_from_outside, 
				// $institution_name, $transfer_from_within, $prev_major, $international_student, 
				// $first_gen_college_student, $live_before_tech, $live_on_campus_fall, 
				// $live_on_campus_spring, $undergrad_research, $undergrad_research_lab_worked, 
				// $undergrad_research_num_semesters, $home_country, $personal_hobby, );


			// , 
			// post_grad_plan, post_grad_plan_desc, expec_graduation, transfer_from_outside, institution_name, 
			// transfer_from_within, prev_major, international_student, first_gen_college_student, live_before_tech, 
			// live_on_campus_fall, live_on_campus_spring, undergrad_research, undergrad_research_lab_worked, 
			// undergrad_research_num_semesters, home_country, personal_hobby
				// '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
			 // '%s', '%s', '%s', '%s', '%s', '%s', '%s'
		$mresult = getDBRegInserted($mentorQuery);

		// //header("Content-type: application/json");
		// // print_r($json);
		echo json_encode($uresult+$mresult);
		
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