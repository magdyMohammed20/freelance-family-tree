<?php
    session_start();
    $pageTitle = 'Admin Dashboard';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

    // Get Latest 5 Registered Users In Array
    $latestUsers = getLatest('*','person',5,'user_Id');
?>

<!-- Start Dashboard Content -->

<!-- Dashboard Statistics -->

<div class="container admin-stats py-4">
<button class="w3-button w3-teal w3-xlarge w3-left w3-open position-absolute" onclick="openLeftMenu()" style='left: 0; '>&#9776;</button>
    
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
                            echo '<li class="py-2 pl-2  my-2 d-flex align-items-center justify-content-between">'.'<span>'.$user['full_name'].'</span>';

                            echo '</li>';
                        }
                        echo '<ul>';
                    }
                    else{ echo '<p class="text-muted text-center m-0">Not Members Founded</p>';}

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
