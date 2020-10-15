<?php

    session_start();
    $pageTitle = 'My Orders';
    include 'init.php';

        if(isset($sessionUser)){

            // Join Comments Table With items,users Tables For Get item_Name And user_Name For Comment Writer
            $stmt = $conn->prepare("SELECT * FROM orders WHERE itemCustomer = ?");
            $stmt->execute(array($sessionUser));
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

                </tr>
            </thead>
            <tbody>
                <?php
                        if(count($orders) == 0){
                            echo '<div class="alert alert-info">No Orders Founded<div>';
                        }
                        else{
                            foreach($orders as $order){
                                echo '<tr>';
                                    echo '<td>'. $order['order_Id'] .'</td>';
                                    echo '<td>'. $order['itemName'] .'</td>';
                                    echo '<td>'. $order['itemPrice'] .'</td>';
                                    echo '<td>'. $order['itemCustomer'] .'</td>';
                                    echo '<td>'. $order['itemSeller'] .'</td>';
                                    echo '<td>'. $order['OrderDate'] .'</td>';

                                //echo '<td>'. $comment['comment_date'] .'</td>';
                                /*
                                echo "<td class='text-left'>".
                                    "<a href='comments.php?do=Edit&c_id=" .$comment['c_id'].
                                    "'class='btn btn-primary mr-1'>Edit <i class='far fa-edit'></i></a>".
                                    "<a href='comments.php?do=Delete&c_id=" .$comment['c_id'].
                                    "'class='btn btn-danger text-white'>Delete
                                    <i class='far fa-trash-alt'></i></a>
                                    ";


                                echo    '</td>';*/
                                echo '</tr>';
                            }
                        }

                        ?>

            </tbody>
        </table>
    </div>

</div>
<?php }


?>


<style>
    .logo{
        margin-right: 5px !important
    }
    .loginASign {
        display: none !important;
    }
</style>
