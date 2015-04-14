<?php
  include_once("db_helper.php");
  header("Access-Control-Allow-Origin: *");

  function getMaxMenteesPerMentor() {
    print(retrieveMaxMenteesPerMentor());
  }

  function retrieveMaxMenteesPerMentor() {
    $query = "SELECT settingValue FROM GlobalSettings WHERE settingName='MaxMenteesPerMentor';";

    $result = getDBResultRecord($query);

    if (count($result) == 0) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
      die();
    }

    return $result["settingValue"];
  }

  function postMaxMenteesPerMentor($newMax) {
    global $_USER;

    if (!userIsAdmin($_USER['uid'])) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 401 Unauthorized");
      return;
    }

    if (!ctype_digit($newMax)) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 404 Bad Request");
      return;
    }

    $minMaxMenteesPerMentor = calcMinMaxMenteesPerMentor();
    if ($newMax < $minMaxMenteesPerMentor) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 409 Conflict");
      print("The new maximum must be greater than $minMaxMenteesPerMentor.");
      return;
    }

    print(setMaxMenteesPerMentor($newMax));
  }

  function setMaxMenteesPerMentor($newMax) {
    $query = "UPDATE GlobalSettings SET settingValue='$newMax' WHERE settingName='MaxMenteesPerMentor';";
    getDBResultAffected($query);

    //TODO check if the row was actually updated; rows_affected returns 0 if value was the same; see http://php.net/manual/en/pdostatement.rowcount.php#allnotes for workaround

    return $newMax;
  }

  function calcMinMaxMenteesPerMentor() {
    $query = "SELECT COUNT(*) FROM Mentee";
    $result = getDBResultRecord($query);
    $numMentees =  $result["COUNT(*)"];

    $query = "SELECT COUNT(*) FROM Mentor";
    $result = getDBResultRecord($query);
    $numMentors = $result["COUNT(*)"];

    return ceil($numMentees / $numMentors);
  }

  function getMinMaxMenteesPerMentor() {
    $minMenteesPerMentor = calcMinMaxMenteesPerMentor();
    print($minMenteesPerMentor);
  }


  function mentorHasSpace($username){
    $countHasName = sprintf("SELECT TRUE FROM Mentor WHERE Mentor.username = '%s'
      AND (SELECT COUNT(*) FROM Matches WHERE username = mentor_user) < (SELECT settingValue FROM GlobalSettings where settingName = 'MaxMenteesPerMentor')", $username);
    $result = mysql_num_rows(mysql_query($countHasName));
    header("Content-type: application/json");
    echo json_encode($result == 1);
  }
?>