<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php getTitle();?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href='<?php echo $tblCss?>bootstrap.min.css'>
    <link rel="stylesheet" href='<?php echo $tblCss?>all.min.css'>
    <link rel="stylesheet" href='<?php echo $tblCss?>style.css'>
</head>

<body>
    <div>
        <?php
            // If User Reg_Status = 0  Then I Will Display Error Message And Wait For Admin Activation
            if(isset($_SESSION['user'])){

                $status = checkUserStatus($_SESSION['user']);
                if($status != 0){
                    echo '<div class="alert alert-danger mb-0 mt-3 w-75 mx-auto">You Membership Need To Admin Activation</div>';
                }
            }
        ?>

    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
               <!--
                <li class="nav-item active">
                    <a class="nav-link py-3" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>-->
    <li class="mr-auto logo" style='top: 30px; left: 5px;'>
        <a class="d-flex align-items-center text-decoration-none" href="index.php">
            <img src="layout/images/logo.png" style="width: 50px; height: 50px"/>
            <h3 class="text-white ml-2 mb-0">E-Commerce <span style="color: #ee5253">Shop</span></h3>
        </a>
    </li>
                <?php

                        // Get All Categories From DB
                        $categories = getCats();

                        foreach($categories as $cat){
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link py-3" href="categories.php?pageid='.$cat['ID'].'&pagename='.$cat['Name'].'">'.$cat['Name'].'</a>';
                            echo '</li>';
                        }
                ?>


              <?php
                // If User Login I Will Show Profile Link To Go To His Profile
                if(isset($_SESSION['user'])){

                    // Get All User Data To Set It In The Profile
                    $getUser = $conn->prepare('SELECT * FROM users WHERE user_Name = ?');

                    $getUser->execute(array($sessionUser));

                    $info = $getUser->fetch();
?>

                   <li class="nav-item dropdown ml-auto d-flex" style="cursor: pointer">
                   <?php
                        global $conn;
                        // Get Number Of Items That User Put It In His Cart
                        $sql = "SELECT count(*) FROM orders WHERE itemCustomer = ?";
                        $result = $conn->prepare($sql);
                        $result->execute(array($_SESSION['user']));
                        $number_of_rows = $result->fetchColumn();

                    ?>
                    <!-- Cart Basket -->
                    <div class="mr-3">
                        <a href='myOrders.php' class="d-flex align-items-center position-relative h-100">
                            <span style="background-color:#ee5253; top: 5px; right: -10px; width: 20px; height: 20px" class="d-flex justify-content-center align-items-center rounded-pill text-white position-absolute"><?php echo $number_of_rows;?></span>
                        <i class="fas fa-shopping-cart text-white"></i>
                        </a>
                    </div>
                    <a class="nav-link dropdown-toggle logout" id="navbarDropdown" role="button" data-toggle="dropdown">
                      <?php echo substr($_SESSION['user'] , 0 , 10);?>
                      <img src="admin/layout/images/<?php if($info['image'] == null){ echo 'default.png'; }else{echo $info['image'];}?>" class='rounded-pill'/>
                    </a>
                    <div class="dropdown-menu bg-white" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="profile.php">Profile</a>
                      <a class="dropdown-item" href="newad.php">Add New Item</a>
                      <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                  </li>


<?php                    }
                ?>
               <li class="nav-item loginASign d-flex align-content-center align-items-center ml-auto">
                    <a class="nav-link logASign py-3" href="login.php">Login </a>
                    <!--<span class="text-white">/</span><a class="nav-link logASign py-3" href="signup.php">Sign Up</a>-->
                </li>
            </ul>
        </div>
    </nav>

<style>

    .dropdown-menu li a{
        color: #343a40 !important
    }
    .dropdown a:first-child{
        cursor: pointer;
    }
    .dropdown img{
        width:40px;
        height: 40px;
    }
</style>


<script>
    var drop = document.getElementById('navbarDropdown'),
        menu = document.getElementsByClassName('dropdown-menu')[0];
    var open = true;

    drop.onclick = function(){
      if(open){
          menu.style.display = 'block';
          open = false;
      }else{
          menu.style.display = 'none';
          open = true;
      }
    };



</script>
