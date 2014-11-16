<?php
	require_once 'db_helper.php';
		global $_USER;		
		$data = json_decode(file_get_contents("php://input"));
		$fname = mysqli_real_escape_string($data->fname);
		$lname = mysqli_real_escape_string($data->lname);
		$phone = mysqli_real_escape_string($data->phone);
		$email = mysql_real_escape_string($data->email);
		$pref_comm = mysqli_real_escape_string($data->pref_comm);
		$user = $_USER['uid'];

		// $query = "INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication) 
		// 			VALUES (" + $_USER['uid'] + ", '$lname', '$fname','$phone','$email','$pref_comm')";
		// $query_result = getDBRegInserted($query);
		// echo json_encode($query_result);
		// if ($query_result) {
		// 	$arr = array('msg'->"Mentor Added Successfully", 'error'->'');
		// 	$json = json_encode($arr);
		// } else {
		// 	$arr = array('msg'->"", 'error'->'Error in inserting Mentor');
		// 	$json = json_encode($arr);
			
		$dbQuery = "INSERT INTO User (username, last_name, first_name, phone_num, email, pref_communication)
					VALUES ('$user', '$lname','$fname','$phone','$email','$pref_comm')";
		$result = getDBRegInserted($dbQuery);

		header("Content-type: application/json");
		// print_r($json);
		echo json_encode($result);
		

?>