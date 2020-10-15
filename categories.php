<?php
    // If User Already Login I Will Start Session For Show User Name , Image
    // In Navbar
    if(count($_COOKIE) > 0){
        session_start();
    }
    include 'init.php';
    if(isset($_GET['pagename'])){


        // Get Items Depending On Id
        $items = getItems('Cat_Id',$_GET['pageid']);
        echo '<div class="container mt-4"><div class="row">';
        echo '<h2 class="w-100 rounded text-white px-3 py-2 mt-0 mb-4" style="background-color: #343a40 "><i class="fas fa-store-alt mr-2 fa-sm" style="color: #ee5253"></i>' . $_GET['pagename'] .'</h2>';
        // If There Is No Items Display Error Message Else Display Items
        if(count($items) == 0){
            echo '<div class="alert alert-danger w-75 mx-auto mt-4">Sorry There Is No Items To Show</div>';

        }
        else{
            foreach($items as $item){

?>
        <div class="col-3 p-2">
            <div class="card position-relative">
             <?php
                if($item['selled'] == 1){
                    echo "<div style='left: 0; top: 0;' class='w-100 position-absolute text-white py-2 bg-dark text-white text-center text-warning font-weight-bold'>Item Is Selled</div>";
                }
             ?>
              <span class="position-absolute price text-white p-1"><?php echo $item['Price'];?></span>
              <img src="<?php echo $item['Image'];?>" class="card-img-top" alt="...">
              <div class="card-body">
               <span class="text-muted"><i class="far fa-calendar-alt mr-2"></i><?php echo $item['Adding_Date'];?></span>
                                            <?php
                            // For Get Item Publisher Name And Set It Inside The Card
                              global $conn;

                              $stmt = $conn->prepare("SELECT user_Name FROM users WHERE user_Id=?");

                              $stmt->execute(array($item['Member_Id'])); // Execute The Query

                              $publisher = $stmt->fetch();

                              echo '<div class="mt-2 text-muted" ><i class="fas fa-user-tie mr-2" style="color: #ee5253"></i>'.$publisher['user_Name'].'</div>';

                              ?>
                <h5 class="card-title font-weight-bold mt-2"><?php echo $item['Name'];?></h5>
                <p class="card-text text-muted">
                    <?php
                        if(strlen($item['Description']) <= 140){

                            echo $item['Description'];
                        }else{
                            $sub = substr($item['Description'] , 0 , 140);
                            echo $sub . '...';
                        }
                    ?>
                </p>
                <a href="
                    <?php
                        // If Item Not Selled I Will Set Decription Page Link
                        // Else I Will Cancel The Link
                        if($item['selled'] == 0){
                            echo "items.php?item_Id=". $item['item_Id'];
                        }else{
                            echo '';
                        }
                    ?>
                "

                class="stretched-link"></a>
              </div>
            </div>
        </div>

<?php
            }

            echo '</div></div>';
        }
    }

    include $tmp . 'footer.php';
?>

<style>
    .loginASign{
        <?php
            // If Cookie Exist Then I Will Hide Login And SignUp From Site
            if(count($_COOKIE) > 1){
                echo 'display: none !important';
            }else{
                echo 'display: flex !important';
            }
        ?>
    }
    .logo{
        margin-right: 5px !important
    }
    .card-img-top{
        height: 180px;
    }

    .card-body{
        height: 245px;
    }
</style>
