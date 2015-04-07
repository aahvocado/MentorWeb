<?php
  include 'db_helper.php';
  header("Access-Control-Allow-Origin: *");

  function getMaxMenteesPerMentor() {
    $settingName = "MaxMenteesPerMentor";
    $colName = "settingValue";
    $query = "SELECT $colName FROM GlobalSettings WHERE settingName='$settingName'";
    $result = getDBResultsArray($query);

    if (count($result) == 0) {
      $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
      die();
    }

    print($result[0][$colName]);
  }
?>