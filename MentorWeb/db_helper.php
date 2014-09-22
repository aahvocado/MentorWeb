<?php
    include 'db_credentials.php';
	
    $connection = mysql_connect(
                                $db_host,
                                $db_username,
                                $db_password
                                );
    
    if(!$connection){
        die("Error connecting to the database.<br/><br/>" . 		
            mysql_error());
    }
    
    $db_select = mysql_select_db($db_database);
    if(!$db_select){die("Error with db select.<br/><br/>".mysql_error());}
    
    function getDBResultsArray($dbQuery,$dieOnError=True){
        $dbResults=mysql_query($dbQuery);
        
        if(!$dbResults){
            if($dieOnError){
                $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
                die();
            }else{
                throw new Exception('Query Execute Failed');
            }
        }
        
        $resultsArray = array();
        if(mysql_num_rows($dbResults) > 0){
            while($row = mysql_fetch_assoc($dbResults)){
                $resultsArray[] = $row;
            }	
        }else{
            if($dieOnError){
                $GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
                die();
            }else{
                throw new Exception('No Records Found');
            }
        }
        
        return $resultsArray;
    }
    
    function getDBResultRecord($dbQuery,$dieOnError=True){
        $dbResults=mysql_query($dbQuery);
        
        if(!$dbResults){
            if($dieOnError){		
                $GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
                die();
            }else{
                throw new Exception('Query Execute Failed');
            }
        }
        
        if(mysql_num_rows($dbResults) != 1){
            if($dieOnError){		
                $GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
                die();
            }else{
                throw new Exception('Not Exactly 1 Record Found');
            }
        }
        return mysql_fetch_assoc($dbResults);
    }
    
    function getDBResultAffected($dbQuery,$dieOnError=True){
        $dbResults=mysql_query($dbQuery);
        if($dbResults){
            return array('rowsAffected'=>mysql_affected_rows());
        }else{
            if($dieOnError){		
                $GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
                die(mysql_error());
            }else{
                throw new Exception('No Records Affected');
            }
        }
    }
    
    function getDBResultInserted($dbQuery,$id,$dieOnError=True){
        $dbResults=mysql_query($dbQuery);
        if($dbResults){
            if($id){
                return array($id=>mysql_insert_id());
            }else{
                return array('rowsAffected'=>mysql_affected_rows());
            }
        }else{
            if($dieOnError){
                $GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
                die(mysql_error());
            }else{
                throw new Exception('No Records Inserted');
            }
        }
    }
    
    function beginDBTransaction(){
        mysql_query("START TRANSACTION");
        mysql_query("BEGIN");
    }
    
    function commitDBTransaction(){
        mysql_query("COMMIT");
    }
    
    function rollbackDBTransaction(){
        mysql_query("ROLLBACK");
    }
    
    function performDBTransaction($dbQueries){
        mysql_query("START TRANSACTION");
        mysql_query("BEGIN");
        $dbResultsAffected = array();
        foreach($dbQueries as $dbQuery){
			$dbResults=mysql_query($dbQuery);
			if($dbResults){
				$dbResultsAffected[]=mysql_affected_rows();
			}else{
				mysql_query("ROLLBACK");
				return NULL;
			}
        }
        mysql_query("COMMIT");
        
        return $dbResultsAffected;
    }
    
    ?>