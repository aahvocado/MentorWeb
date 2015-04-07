<?php
  include 'db_helper.php';
  header("Access-Control-Allow-Origin: *");

  $settingName = "MaxMenteesPerMentor";
  $colName = "settingValue";

  function getMaxMenteesPerMentor() {
    global $settingName, $colName;

    $query = "SELECT $colName FROM GlobalSettings WHERE settingName='$settingName';";
    $result = getDBResultsArray($query);

    if (count($result) == 0) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
      die();
    }

    print($result[0][$colName]);
  }

  function setMaxMenteesPerMentor($newMax) {
    global $_USER;

    if (!userIsAdmin($_USER['uid'])) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 401 Unauthorized");
      return;
    }

    if (!ctype_digit($newMax)) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 404 Bad Request");
      return;
    }

    global $settingName, $colName;

    $query = "UPDATE GlobalSettings SET $colName='$newMax' WHERE settingName='$settingName';";
    $result = getDBResultAffected($query);

    if ($result['rowsAffected'] != 1) {
      $GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
      return;
    }

    print($newMax);
  }
?>