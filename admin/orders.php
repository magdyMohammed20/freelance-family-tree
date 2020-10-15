<?php
    session_start();
    $pageTitle = 'All Orders';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

        // Check The Query
        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        if($do == 'Manage'){

            // Join Comments Table With items,users Tables For Get item_Name And user_Name For Comment Writer
            $stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_Id DESC");
            $stmt->execute();
            $orders = $stmt->fetchAll();

        ?>
<h1 class="text-center mt-3">All Orders</h1>
<div class="container">
    <div class="table-responsive mt-4">
        <table class="table members-table shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Item Price</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Controls</th>

                </tr>
            </thead>
            <tbody>
                <?php

                if(count($orders) == 0){
                    echo '<div class="alert alert-info">No Orders Founded<div>';
                }else{
                    foreach($orders as $order){
                                echo '<tr>';
                                    echo '<td>'. $order['order_Id'] .'</td>';
                                    echo '<td>'. $order['itemName'] .'</td>';
                                    echo '<td>'. $order['itemPrice'] .'</td>';
                                    echo '<td>'. $order['itemCustomer'] .'</td>';
                                    echo '<td>'. $order['itemSeller'] .'</td>';
                                    echo '<td>'. $order['OrderDate'] .'</td>';

                                //echo '<td>'. $comment['comment_date'] .'</td>';

                                echo "
                                <td class='text-left d-flex justify-content-center'>
                                    <a href='orders.php?do=Delete&order_Id=".$order['order_Id'].
                                "'class='btn btn-danger text-white'>
                                <i class='far fa-trash-alt fa-sm'></i></a>
                                ";


                                echo    '</td>';


                                echo '</tr>';

                            }
                }

                ?>

            </tbody>
        </table>
    </div>

</div>
<?php }



        else if($do == 'Delete'){
            // Check For Comment Id That Want To Update The Data
            $orderId = isset($_GET['order_Id']) && is_numeric($_GET['order_Id'])?intval($_GET['order_Id']):0;


            $check = checkItems('order_Id', 'orders' ,$orderId);

            if($check > 0){
                // Select Data Depending On Comment Id
                $stmt = $conn->prepare("
                    DELETE
                    FROM orders
                    WHERE order_Id=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($orderId));

                if($stmt->rowCount() > 0){
                    echo '<div class="alert alert-success w-75 mx-auto mt-4">Order Deleted Successfully</div>';
                }else{
                    redirectHome('Page Not Founded' , 10);
                }
            }
        }
        //include $tmp . 'footer.php';

    }else{
        header('Location: index.php');
        exit();
    }
