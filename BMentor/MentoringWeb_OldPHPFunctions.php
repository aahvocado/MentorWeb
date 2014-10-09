<?php
	include 'db_helper.php';

	function welcome() {
		global $_USER;
		$userid = array('username' => $_USER['uid']);

		$type = "Mentee";
		$dbQuery = sprintf("SELECT FirstName, LastName FROM Mentees WHERE Username = '%s'",
			$_USER['uid']);
		$result = getDBResultsArray($dbQuery);

		if (empty($result)) {
			$dbQuery = sprintf("SELECT FirstName, LastName FROM Mentors WHERE Username = '%s'",
			$_USER['uid']);
			$result = getDBResultsArray($dbQuery);
			$type = "Mentor";
		}

		if (empty($result)) {
			$dbQuery = sprintf("SELECT FirstName, LastName FROM Admin WHERE Username = '%s'",
			$_USER['uid']);
			$result = getDBResultsArray($dbQuery);
			$type = "Admin";
		}
		//echo $type;
		//array_push($result, $type);
		 $result["0"]["UserType"] = $type;
		// echo var_dump($result);
		// echo $result["0"]["UserType"];

		header("Content-type: application/json");
		echo json_encode($result);
	}

?>