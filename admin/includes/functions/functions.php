<?php

    function getTitle(){

        global $pageTitle;
        if(isset($pageTitle)){

            echo $pageTitle;
        }
    }

    // Get Total Number Of Rows In Spacific Table
    function getTotalRows($col,$table){
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT($col) FROM $table");
        $stmt->execute();
        $count = $stmt->fetchColumn(); // For Fetch The Count Row

        return $count;
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
