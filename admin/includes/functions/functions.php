<?php

    function getTitle(){

        global $pageTitle;
        if(isset($pageTitle)){

            echo $pageTitle;
        }
    }

    // Redirect Function
    // Redirect User To Homepage If The Requested Page Not Defined Or Not Exist
    // After Specific Seconds
    function redirectHome($errMsg , $seconds = 3){
        echo '<div class="alert alert-danger w-75 mx-auto mt-4">'.$errMsg.'</div>';
        echo '<div class="alert alert-info w-75 mx-auto">You Will Redirect To Home Page After '.$seconds.' Seconds</div>';
        header('refresh: '.$seconds.' ; url=index.php');
        exit(); // For Prevent Any Action After Redirection
    }

    // For Check UserName Duplications Or Products Duplications
    function checkAny($select, $from ,$where){
        global $conn;
        // Select Data Depending On UserId
        $stmt= $conn->prepare("
            SELECT $select
            FROM $from
            WHERE $select = ?
            "
        );
        // Excute Query
        $stmt->execute(Array($where));

        $checkCount = $stmt->rowCount();
        // Check If Row Exist Will Display Error Message And Don't
        // Execute Another Code After Them
        if ( $checkCount > 0 ){
            // Redirect To Admin Home Page After 5 Seconds
            redirectHome('User Name Already Exist' , 5);
            exit();
        }

    }


    // For Check UserName Duplications Or Products Duplications
    function checkItems($select, $from ,$where){
        global $conn;
        // Select Data Depending On UserId
        $stmt= $conn->prepare("
            SELECT $select
            FROM $from
            WHERE $select = ?
            "
        );
        // Excute Query
        $stmt->execute(Array($where));

        $checkCount = $stmt->rowCount();

        return $checkCount;

    }

    // Get Total Number Of Rows In Spacific Table
    function getTotalRows($col,$table){
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT($col) FROM $table");
        $stmt->execute();
        $count = $stmt->fetchColumn(); // For Fetch The Count Row

        return $count;
    }

    // Get Total Number Of Rows In Spacific Table With Condition
    function getTotalRowsWithCondition($col,$table,$where,$value){
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT($col) FROM $table WHERE $where=$value");
        $stmt->execute();
        $count = $stmt->fetchColumn(); // For Fetch The Count Row

        return $count;
    }

    // Check If Member Activated Or Not
    function checkActivated($select, $from ,$where){
        global $conn;
        $check = false;
        // Select Data Depending On UserId
        $stmt= $conn->prepare("
            SELECT $select
            FROM $from
            WHERE $select = ?
            "
        );
        // Excute Query
        $stmt->execute(Array($where));

        $checkCount = $stmt->rowCount();

        if ( $checkCount > 0 ){
            $check = true;
        }

        return $check;
    }

    // Get Latest Registered Members And Items
    function getLatest($select,$table){
        global $conn;

        $stmt = $conn->prepare("SELECT $select FROM $table");

        $stmt->execute(); // Execute The Query

        $rows = $stmt->fetchAll(); // Fetch All 5 Rows

        return $rows;
    }
?>
