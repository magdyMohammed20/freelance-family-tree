<?php

    session_start();
    $pageTitle = 'Cart';
    include 'init.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($sessionUser)){

            $itemId = $_POST['itemId'];
            $itemName = $_POST['itemName'];
            $itemPrice = $_POST['itemPrice'];
            $itemCustomer = $_POST['itemCustomer'];
            $itemSeller = $_POST['itemSeller'];

            // Validate Inserted User Data
            // Array For Store Errors And Display It
            $formErrors = Array();
            if(empty($itemName)){
                // Append Message To Array
                $formErrors[] = "Item Name Can't Be Empty";
            }
            if(empty($itemPrice)){
                // Append Message To Array
                $formErrors[] = "Item Price Can't Be Empty";
            }
            if(empty($itemCustomer)){
                // Append Message To Array
                $formErrors[] = "You Should Login First <a href='login.php'>Login</a>";
            }
            if(empty($itemSeller)){
                // Append Message To Array
                $formErrors[] = "Item Seller Can't Be Empty";
            }


            // If There Aren't Errors Update The DB
            if(count($formErrors) > 0){
                // Display All Errors For User
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger w-75 mx-auto my-3 shadow'>".  $error ."</div>";
                }
            }else{
                // Update Data Depending On UserId
                $stmt = $conn->prepare("
                    INSERT INTO orders (itemName,itemPrice,itemCustomer,itemSeller,OrderDate)
                    VALUES(? , ? , ? ,?,now())
                ");
                // Excute Query
                $stmt->execute(Array($itemName,$itemPrice,$itemCustomer,$itemSeller));


                // Update Item Data To Be Selled
                $stmt2 = $conn->prepare("
                    UPDATE items
                    SET selled = ?
                    WHERE item_Id = ?
                ");
                // Excute Query
                $stmt2->execute(array(1,$itemId));
                echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow">Order Added Successfully</div>';


            }

    }
    }

?>


<style>
    .logo{
        margin-right: 5px !important
    }
    .loginASign {
        display: none !important;
    }
</style>
