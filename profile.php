<?php

    session_start();

    $pageTitle = 'Profile';
    include 'init.php';

    // Check If User Login I Will Show All Data And Profile Else Will Direct Me To Login Page
    if($sessionUser){
        // Get All User Data To Set It In The Profile
        $getUser = $conn->prepare('SELECT * FROM users WHERE user_Name = ?');

        $getUser->execute(array($sessionUser));

        $info = $getUser->fetch();

?>
    <div class="container mt-4">
            <h1>
                <?php echo $_SESSION['user'] . ' Profile';?>
            </h1>
            <div class="row">
                <div class="information col-6 mt-3">
                    <div class="card position-relative">
                        <div class="card-header text-white">
                            <i class="fas fa-table fa-lg mr-2"></i>Profile Information
                        </div>
                        <div class="card-body p-3">
                           <img src="admin/layout/images/<?php if($info['image'] == null){ echo 'default.png'; }else{echo $info['image'];}?>" class='user-img rounded-pill mb-2 position-absolute' style='width: 50px; height: 50px;'/>
                            <p><i class="far fa-user mr-2"></i>User Name : <?php echo $sessionUser;?></p>
                            <p><i class="far fa-envelope mr-2"></i>Email : <?php echo $info['user_Email'];?></p>
                            <p><i class="fas fa-user-friends mr-2"></i>Full Name : <?php echo $info['user_fullName'];?></p>
                            <p><i class="far fa-calendar-alt mr-2"></i>Registration Date : <?php echo $info['reg_date'];?></p>
                            <!-- Hidden Form For Send User Data To Another Page To Enable User To Edit It -->
                            <form action='edituser.php' method="POST">
                               <input type="hidden" name="userName" value="<?php echo $sessionUser;?>"/>
                               <input type="hidden" name="userEmail" value="<?php echo $info['user_Email'];?>"/>
                               <input type="hidden" name="userFullName" value="<?php echo $info['user_fullName'];?>"/>
                               <input type="submit" class="float-right btn text-white" style="background-color: #ee5253" value="Edit Information">
                            </form>
                        </div>

                    </div>
</div>
                <div class="information col-6 mt-3">
                    <div class="card">
                        <div class="card-header text-white">
                            <i class="far fa-comment fa-lg mr-2"></i>Latest Comments
                        </div>
                        <div class="card-body p-3">
        <?php
            // If Member Has Comments Then Display It In His Profile Else Display Empty Message
            $stmt = $conn->prepare("SELECT comments.*
                                    FROM comments
                                    WHERE user_id=?
                                    ");
            $stmt->execute(array($info['user_Id']));
            $Mycomments = $stmt->fetchAll();
            if(count($Mycomments) == 0){
                echo '<p class="m-0 text-muted">There Is No Comments</p>';
            }else{
                foreach($Mycomments as $Mycomm){
                    echo "<p class='m-0'>" . $Mycomm['comment'] . "</p>";
                }
            }
        ?>
                        </div>
                    </div>
</div>
                <div class="information col-12 mt-3">
                    <div class="card">
                        <div class="card-header text-white">
                            <i class="fab fa-buysellads fa-lg mr-2"></i>My Items
                        </div>
                        <div class="card-body p-3">
                            <?php
                                // Get Items Depending On Id
                                $items = getItems('Member_Id',$info['user_Id'],1);
                                echo '<div class="container"><div class="row">';
                                // If There Is No Items Display Error Message Else Display Items
                                if(count($items) == 0){
                                    echo '<p class="text-muted m-0">No Items Founded <a href="newad.php" class="">Create New Item <i class="fa fa-plus ml-1"></i></a></p>';
                                    echo '</div></div>';
                                }
                                else{
                                    foreach($items as $item){
                            ?>
                            <div class="col-3 p-2">
                                <div class="card item position-relative">
                                 <?php
                                    $linkActive = '';
                                    if($item['Approve'] == 0){
                                        $linkActive = '';
                                        echo '<div class="notApproved badge badge-danger rounded-0 p-2 position-absolute w-100" style="right:0;">Not Approved</div>';
                                    }else{
                                        $linkActive = $item['item_Id'];
                                    }
                                    ?>
                                  <span class="position-absolute w-25 text-center price text-white p-1"><?php echo $item['Price'];?></span>
                                  <img src="<?php echo $item['Image'];?>" class="card-img-top" alt="..." style='height: 200px'>
                                  <div class="card-body">
                                    <h5 class="card-title font-weight-bold mt-2"><?php echo $item['Name'];?></h5>
                                    <p class="card-text text-muted">
                                       <?php

                                            if(strlen($item['Description']) <= 75){

                                                echo $item['Description'];
                                            }else{
                                                $sub = substr($item['Description'] , 0 , 67);
                                                echo $sub . '...';
                                        }?>
                                    </p>
                                    <span class="text-muted ml-auto d-block text-right" style="font-style:italic"><?php echo $item['Adding_Date'];?><i class="far fa-calendar-alt ml-2"></i></span>
                                    <!-- If Item Is Approved I Will Set The Link Of The Item Else The href Will Be Empty -->
                                    <a href="<?php if($item['Approve'] == 1){echo 'items.php?item_Id='.$linkActive ;}?>" class="stretched-link"></a>

                                  </div>
                                </div>
                            </div>

        <?php
                    }

                    echo '</div>';
                    echo '<a href="newad.php" class="btn text-white mt-3" style="background-color: #343a40">Add New Item <i class="fa fa-plus ml-1" style="color: #ee5253"></i></a>';
                    echo '</div>';
                                }?>
                        </div>
                    </div>
</div>
            </div>
    </div>
<?php }
      else{
        // If Login Without Enter Login Data Will Direct Me To Login Page
        header('Location: login.php');
      }

      include $tmp . 'footer.php';
?>

<style>
    .loginASign{
        <?php
            // If Cookie Exist Then I Will Hide Login And SignUp From Site
            if(isset($_SESSION['user'])){
                echo 'display: none !important';
            }
            ?>
    }

    .logo{
        margin-right: 5px !important
    }
    .card-header{
        background-color: #343a40;
        border-bottom: 4px solid #ee5253;
    }
    .card-header i{
        color: #ee5253
    }
    .user-img{
        top: 25px;
        right: 15px;
        border: 3px solid #FFF;
    }

    .item{
        transition: all .1s linear;
    }
    .item:hover{
        box-shadow: 1px 1px 10px 3px #DDD;
        transform: translateY(-3px);
    }
</style>
