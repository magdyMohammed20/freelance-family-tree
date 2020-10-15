<?php
    session_start();
    $pageTitle = 'Manage Comments';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

        // Check The Query
        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        if($do == 'Manage'){

            // Join Comments Table With items,users Tables For Get item_Name And user_Name For Comment Writer
            $stmt = $conn->prepare("SELECT comments.*,items.Name,users.user_Name
                                    FROM comments
                                    INNER JOIN items
                                    ON items.item_Id = comments.item_id
                                    INNER JOIN users
                                    ON users.user_Id = comments.user_id");
            $stmt->execute();
            $comments = $stmt->fetchAll();

        ?>
<h1 class="text-center mt-3">Manage Comments</h1>
<div class="container">
    <div class="table-responsive mt-4">
        <table class="table members-table shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Added Date</th>
                    <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php

                            foreach($comments as $comment){
                                echo '<tr>';
                                    echo '<td>'. $comment['c_id'] .'</td>';
                                    echo '<td>'. $comment['comment'] .'</td>';
                                    echo '<td>'. $comment['Name'] .'</td>';
                                    echo '<td>'. $comment['user_Name'] .'</td>';

                                echo '<td>'. $comment['comment_date'] .'</td>';
                                echo "<td class='text-left'>".
                                    "<a href='comments.php?do=Edit&c_id=" .$comment['c_id'].
                                    "'class='btn btn-primary mr-1'>Edit <i class='far fa-edit'></i></a>".
                                    "<a href='comments.php?do=Delete&c_id=" .$comment['c_id'].
                                    "'class='btn btn-danger text-white'>Delete
                                    <i class='far fa-trash-alt'></i></a>
                                    ";

                                if($comment['status'] == 0){
                                    echo '<a class="btn btn-warning text-white" href="comments.php?do=approve&c_id='.$comment["c_id"].'"'.'>Approve <i class="fas fa-check"></i></a>';
                                }
                                echo    '</td>';
                                echo '</tr>';
                            }

                        ?>

            </tbody>
        </table>
    </div>

</div>
<?php }


        else if($do == 'Edit'){
            // Check For commentId That Want To Update The Data
            $commentId = isset($_GET['c_id']) && is_numeric($_GET['c_id'])?intval($_GET['c_id']):0;

            // Select Data Depending On UserId
            $stmt = $conn->prepare("
                SELECT *
                FROM comments
                WHERE c_id=?
                LIMIT 1"
            );
            // Excute Query
            $stmt->execute(Array($commentId));
            // Fetch Data
            $data = $stmt->fetch(); // Fetch Row Data As Array

            // Check If Row Exist
            if($stmt->rowCount() > 0){

            ?>
<!-- If Row Exist Show The Form Of Update The Data -->
<h1 class="text-center font-weight-bold my-3">Edit Comment</h1>
<div class="container">
    <form class="edit-form" action="?do=Update" method="POST">
        <input type="hidden" name='commId' value="<?php echo $commentId;?>">
        <div class="form-group my-3">
            <label class="mt-1 p-0 d-block">Comment</label>
            <textarea class="w-100" name="comment">
                <?php echo $data['comment'];?>
            </textarea>
        </div>
        <button class="btn btn-primary col-3 mt-3">Save</button>
    </form>
</div>
<?php }


            else{

                 redirectHome('Page Not Founded',5);
?>

<?php }

        }
        else if($do == 'Update'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo '<h1 class="text-center">Update Comment</h1>';
                $commId = $_POST['commId'];
                $comment = $_POST['comment'];



                // Update Data Depending On Comment Id
                $stmt = $conn->prepare("
                    UPDATE comments
                    SET comment=?
                    WHERE c_id=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($comment,$commId));

                // Print Number Of Updated Records
                echo '<div class="alert alert-success w-75 mx-auto mt-4">'. $stmt->rowCount() .' Record Updated</div>';

            }else{
                redirectHome('You Can\'t Access This Page Directly',5);
            }
        }

        else if($do == 'Delete'){
            // Check For Comment Id That Want To Update The Data
            $commId = isset($_GET['c_id']) && is_numeric($_GET['c_id'])?intval($_GET['c_id']):0;

            $check = checkItems('c_id', 'comments' ,$commId);

            if($check > 0){
                // Select Data Depending On Comment Id
                $stmt = $conn->prepare("
                    DELETE
                    FROM comments
                    WHERE c_id=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($commId));

                if($stmt->rowCount() > 0){
                    echo '<div class="alert alert-success w-75 mx-auto mt-4">Comment Deleted Successfully</div>';
                }else{
                    redirectHome('Page Not Founded' , 10);
                }
            }
        }
        else if($do == 'approve'){
            // Check For User_Id That Want To Update The Data
            $commId = isset($_GET['c_id']) && is_numeric($_GET['c_id'])?intval($_GET['c_id']):0;

            // Check If Comment Id Is Exist In DB Or Not
            $check = checkActivated('c_id','comments',$commId);

            // If Comment Id Exist Then Update Comment To Be Approved
            if($check == true){
                // Select Data Depending On UserId
                $stmt = $conn->prepare("
                    UPDATE comments
                    SET status = 1
                    WHERE c_id=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($commId));

                echo '<div class="alert alert-success w-75 mx-auto mt-4">Record Updated Successfully And Comment Approved</div>';
            }
            // If User Id Not Exist Then Show Error Message
            else{
                echo '<div class="alert alert-danger w-75 mx-auto mt-4">Failed To Approve Comment</div>';
            }
        }
        //include $tmp . 'footer.php';

    }else{
        header('Location: index.php');
        exit();
    }
