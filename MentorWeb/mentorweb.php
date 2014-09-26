<?php
	include 'db_helper.php';
	
	function addUser() {
		global $_USER;
		$user = $_USER["uid"];
		$dbQuery = sprintf("INSERT INTO User (username, first_name) 
			VALUES ($user, $_USER)");
		$result = getDBResultAffected($dbQuery);
		echo json_encode($result);
	}

	function listMatches() {
		$dbQuery = sprintf("SELECT mentorId,menteeId,semester FROM matches");
		$result = getDBResultsArray($dbQuery);
		error_log("test");
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	function getMatch($id) {
		$dbQuery = sprintf("SELECT mentorId,menteeId,semester FROM matches WHERE mentorId = '%s'",
			mysql_real_escape_string($id));
		$result=getDBResultRecord($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}
	
	function addMatch($comment) {
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
	
	function deleteMatch($id) {
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

	function getUserMatch($user, $id) {
		$dbQuery = sprintf("SELECT id,comment FROM usercomments WHERE user = '%s' AND id = '%s'",
			mysql_real_escape_string($user), mysql_real_escape_string($id));
		$result=getDBResultRecord($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}

	function listUserMatches($user, $id) {
		$dbQuery = sprintf("SELECT id,comment FROM usercomments WHERE user = '%s'",
			mysql_real_escape_string($user));
		$result=getDBResultsArray($dbQuery);
		header("Content-type: application/json");
		echo json_encode($result);
	}
?>
