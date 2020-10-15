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
    function redirectToSignUp($errMsg , $seconds = 3){
        echo '<div class="alert alert-danger w-75 mx-auto mt-4">'.$errMsg.'</div>';
        echo '<div class="alert alert-info w-75 mx-auto">You Will Redirect To Sign Up Page After '.$seconds.' Seconds</div>';
        header('refresh: '.$seconds.' ; url=signup.php');
        exit(); // For Prevent Any Action After Redirection
    }


    // Get Categories From DB To Display It In User Navbar
    function getCats(){
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY ID ASC");

        $stmt->execute(); // Execute The Query

        $cats = $stmt->fetchAll();

        return $cats;
    }

    // Get Items From DB And Pass Page Id For Get Items Depending On Items Id To Show It In categories Page
    // Set $approve For Display Approved Items Only
    function getItems($where,$value,$approve = NULL){
        global $conn;

        // For Show Approved Items Only
        if($approve == NULL){
            $sql = 'AND Approve = 1';
        }else{
            $sql = NULL;
        }
        $stmt = $conn->prepare("SELECT * FROM items WHERE $where=? $sql ORDER BY item_Id DESC");

        $stmt->execute(array($value)); // Execute The Query

        $items = $stmt->fetchAll();

        return $items;
    }

    // Check User Status
    function checkUserStatus($user){
        global $conn;

        $stmtx = $conn->prepare('SELECT user_Name,reg_status
                                FROM users
                                WHERE user_Name=?
                                AND reg_status=0
                                ');
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();
        return $status;
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
            redirectToSignUp('User Name Already Exist' , 5);
            exit();
        }

    }

    // For Get All Items From DB And Display It In HomePage
    function getAll($tableName,$order){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE Approve=1 ORDER BY $order DESC");
        $stmt->execute();
        $all = $stmt->fetchAll();

        return $all;
    }

    // For Get Last 5 Items Added From DB And Display It In HomePage
    function getLast8Rows($tableName,$order){
            global $conn;
            $stmt = $conn->prepare("SELECT * FROM $tableName WHERE Approve=1 ORDER BY $order DESC LIMIT 7");
            $stmt->execute();
            $all2 = $stmt->fetchAll();

            return $all2;
        }


    // Get The First Row From Table [Used In Slider]
    function getFirstRow($tableName){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE Approve=1 LIMIT 1");
        $stmt->execute();
        $all = $stmt->fetchAll();

        return $all;
    }
?>
