<?php
    session_start();
    $pageTitle = 'Admin Dashboard';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

    // Get Latest 5 Registered Users In Array
    $latestUsers = getLatest('*','person',5,'user_Id');

    // Get Latest 5 Items In Array
    $latestItems = getLatest('*','items',5,'item_Id');


?>

<!-- Start Dashboard Content -->

<!-- Dashboard Statistics -->
<div class="container admin-stats py-4">
    <h1 class="p-2 bg-dark text-white">Dashboard</h1>
    <div class="row mt-4">
        <div class="col-6 text-center p-3">
            <div class="p-4 rounded shadow-sm">
                <p class='mb-1'>Total Members</p>
                <a href="members.php" class="stretched-link">
                    <span class='d-block'><?php echo getTotalRows('id','person');?></span>
                </a>
            </div>
        </div>
        <div class="col-6 text-center p-3">
            <div class="p-4 rounded shadow-sm">
                <p class='mb-1'>Pending Members</p>
                <a href="members.php?pending=pend" class="stretched-link text-decoration-none">
                    <span class='d-block'><?php echo getTotalRowsWithCondition('user_Id','users','reg_status',0); ?></span>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="container admin-stats2">
    <div class="row">
        <div class="col-6 p-2">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users fa-lg mr-2"></i>Latest Registered Users
                </div>
                <div class="card-body p-3">
                   <?php
                    // If There Are Users In DB I Will Show Latest Registered Users
                    if(count($latestUsers) > 0){
                        echo '<ul class="latest-users list-unstyled mb-0">';
                        foreach($latestUsers as $user){
                            echo '<li class="py-1 pl-2  my-2 d-flex align-items-center justify-content-between">'.'<span>'.$user['full_name'].'</span>';

                            /*
                            echo '<div>';
                            echo '<a href="members.php?do=Edit&user_Id='. $user['user_Id']. '" class="p-1 btn btn-primary p-1 text-white mr-1">Edit <i class="far fa-edit fa-sm"></i></a>';
                            if($user['reg_status'] == 0){
                                echo '<a class="p-1 ml-2 btn btn-warning text-white" href="members.php?do=activate&user_Id='.$user["user_Id"].'"'.'>Activate <i class="fas fa-check fa-sm"></i></a>';
                            }
                            echo '</div>';
                            */
                            echo '</li>';
                        }
                        echo '<ul>';
                    }
                    else{ echo '<p class="text-muted text-center m-0">Not Members Founded</p>';}

                    ?>

                </div>
            </div>
        </div>

        <div class="col-6 p-2">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-sitemap fa-lg mr-2"></i>Latest Added Items
                </div>
                <div class="card-body p-3">
                    <?php
                        // If There Are Items In DB I Will Show Latest Registered Items
                        if(count($latestItems) > 0){
                            echo '<ul class="latest-users list-unstyled mb-0">';
                            foreach($latestItems as $item){
                                echo '<li class="py-1 pl-2  my-2 d-flex align-items-center justify-content-between">'.'<span>'.$item['Name'].'</span>';

                                echo '<div>';
                                echo '<a href="items.php?do=Edit&item_Id='. $item['item_Id']. '" class="p-1 btn btn-primary p-1 mr-1 text-white">Edit <i class="far fa-edit fa-sm"></i></a>';
                                                             // If Item Isn't Approved I Will Display Approved Button Inside Item Table That Redirect Me To Approve Page For Item ApprovingTable
                                if($item['Approve'] == 0){
                                    echo '<a class="btn btn-warning text-white p-1 mr-1" href="items.php?do=Approve&item_Id='.$item["item_Id"].'"'.'>Approve <i class="fas fa-check"></i></a>';
                                }
                                echo '</div>';
                                echo '</li>';
                            }
                            echo '<ul>';
                        }

                        else{
                            echo '<p class="text-muted text-center m-0">Not Items Founded</p>';
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-6 p-2">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-comment fa-lg mr-2"></i>Latest Comments
                </div>
                <div class="card-body p-3">
                    <?php
                    // Join Comments Table With users Tables For Get user_Name For Comment Writer
                    $stmt = $conn->prepare("SELECT comments.*,users.user_Name
                                            FROM comments
                                            INNER JOIN users
                                            ON users.user_Id = comments.user_id
                                            ORDER BY c_id DESC");
                    $stmt->execute();
                    $latestComments = $stmt->fetchAll();

                        // If There Are Comments In DB I Will Show Latest Comments
                        if(count($latestComments) > 0){
                            echo '<ul class="latest-users list-unstyled mb-0">';
                            foreach($latestComments as $comment){
                                echo '<li class="py-2 pl-2  my-2 d-flex flex-column">';
                                echo '<p class="d-block"> <b>Member Name : </b>'.$comment['user_Name'].'</p>';
                                echo '<p><b>Comment : </b>'.$comment['comment'].'</p>';
                                echo '<div>';
                                echo '<a href="comments.php?do=Edit&c_id='. $comment['c_id']. '" class="p-1 btn btn-primary p-1 float-right mr-1 text-white">Edit <i class="far fa-edit fa-sm"></i></a>';
                                // If Comment Isn't Approved I Will Display Approved Button Inside Comments Table That Redirect Me To Approve Page For Comment Approving Table
                                if($comment['status'] == 0){
                                    echo '<a class="btn btn-warning text-white p-1 mr-1" href="comments.php?do=approve&c_id='.$comment["c_id"].'"'.'>Approve <i class="fas fa-check"></i></a>';
                                }
                                echo '</div>';
                                echo '</li>';
                            }
                            echo '<ul>';
                        }

                        else{
                            echo '<p class="text-muted text-center m-0">Not Comments Founded</p>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Dashboard Content -->
<?php
        include $tmp . 'footer.php';
    }else{
        header('Location: index.php');
    }

?>
