<?php
    // Homepage
    session_start();
    $pageTitle = 'Home Page';
    include 'init.php';

    // Select All Approved Items From DB To Show It In HomePage
    $allItems = getAll('items','item_Id');

    // Last 8 Items
    $lastItems = getLast8Rows('items','item_Id');

    // First Item To Show In Slider
    $firstItem = getFirstRow('items');

    ?>
<div class="container p-0">
    <div class="row">
        <div class="card col-3 mt-3 p-0" style="background-color: transparent; border: 0">
          <ul class="list-group list-group-flush list-unstyled bg-white border overflow-hidden mr-3">
          <li class="p-3 bg-dark text-white font-weight-bold"><i class="fas fa-archive mr-1" style='color: #ee5253'></i> All Categories</li>
<?php

                        // Get All Categories From DB
                        $categories = getCats();

                        foreach($categories as $cat){
                            echo '<li class="nav-item catLi position-relative">';
                            echo '<a class="nav-link border-bottom py-3 text-dark" href="categories.php?pageid='.$cat['ID'].'&pagename='.$cat['Name'].'"><i class="fas fa-angle-double-right mr-2"></i>'.$cat['Name'].'</a>';
                            echo '</li>';
                        }
                ?>
          </ul>
        </div>
        <div id="carouselExampleIndicators" class="carousel shadow-sm mt-3 p-0 ml-4 col-9 ml-auto slide mb-4" data-ride="carousel">
              <ol class="carousel-indicators">
                <li style="width: 15px; height: 15px; border-radius: 100%"data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <?php
                    for($x = 1; $x < count($lastItems); $x++){
            echo '<li style="width: 15px; height: 15px; border-radius: 100%"data-target="#carouselExampleIndicators" data-slide-to="'.$x.'"></li>';

                    }
                ?>
              </ol>
              <div class="carousel-inner overflow-hidden" style="height: 500px;">

                <?php
                  $firstItemId = '';
                  foreach($firstItem as $f){
                      // Get First Item Id To Prevent Dulpication Of Display The First
                      // Item
                     $firstItemId = $f['item_Id'];
                  ?>

                      <div class="carousel-item position-relative active position-relative" style="height: 500px;">
                        <ul class="list-unstyled position-absolute" style="z-index: 20; top: 20px; left:0;">
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 text-white d-flex align-items-center justify-content-between'>Price : <?php echo $f['Price']?> <i class="fas fa-money-bill-alt float-right"></i></li>
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 my-2 text-white d-flex align-items-center justify-content-between'>Country Made : <?php echo $f['Country_Made']?> <i class="fas fa-flag float-right ml-3"></i></li>
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 my-2 text-white d-flex align-items-center justify-content-between'>Adding Date : <?php echo $f['Adding_Date']?> <i class="far fa-calendar-alt float-right ml-3"></i></li>

                        </ul>
                         <img class="d-block w-100 h-100" src="<?php echo $f['Image'];?>" alt="First slide">
                          <div class="carousel-caption d-none d-md-block">
                            <h2 class="p-2"><?php echo $f['Name'];?></h2>
                            <p><?php echo $f['Description'];?></p>
                          </div>
                          <a href="items.php?item_Id=<?php echo $f['item_Id']; ?>" class="stretched-link" style="z-index: 30"></a>
                      </div>
                      <?php
                  }
                  foreach($lastItems as $item){
                      // If Item Id Equal To Id Of The First Item Don't Duplicate The
                      // Display Of First Item
                      if($item['item_Id'] !== $firstItemId){
                      ?>
                        <div class="carousel-item position-relative overflow-hidden position-relative" style="height: 500px;">
                          <ul class="list-unstyled position-absolute" style="z-index: 20; top: 20px; left:0;">
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 text-white d-flex align-items-center justify-content-between'>Price : <?php echo $item['Price']?><i class="fas fa-money-bill-alt float-right"></i></li>
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 my-2 text-white d-flex align-items-center justify-content-between'>Country Made : <?php echo $item['Country_Made']?> <i class="fas fa-flag ml-3 float-right"></i></li>
                            <li style='background-color:#ee5253; cursor:pointer; transition: all .3s linear; transform: translateX(-195px)' class='p-2 my-2 text-white d-flex align-items-center justify-content-between'>Adding Date : <?php echo $item['Adding_Date']?> <i class="far fa-calendar-alt float-right ml-3"></i></li>
                          </ul>
                          <img class="d-block w-100 h-100" src="<?php echo $item['Image'];?>" alt="First slide">
                          <div class="carousel-caption d-none d-md-block">
                            <h2 class="p-2"><?php echo $item['Name'];?></h2>
                            <p><?php echo $item['Description'];?></p>
                          </div>
                          <a href="items.php?item_Id=<?php echo $item['item_Id']?>" class="stretched-link"></a>
                        </div>
                <?php  }}


                  ?>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
    </div>
</div>


    <div class="container p-0">
        <div class="row">
    <?php
    foreach($allItems as $item){

    ?>

                    <div class="col-3 p-2">
                        <div class="card position-relative item" style="height: 450px;">
                          <?php
                            if($item['selled'] == 1){
                                echo "<div style='left: 0; top: 0;' class='w-100 position-absolute text-white py-2 bg-dark text-white text-center text-warning font-weight-bold'>Item Is Selled</div>";
                            }
                         ?>
                          <span class="position-absolute price text-white p-1 w-25 text-center"><?php echo $item['Price'];?></span>
                          <img src="<?php echo $item['Image'];?>" class="card-img-top" style="height: 200px;">
                          <div class="card-body pb-0">
                           <span class="text-muted" style="font-style: italic"><i class="far fa-calendar-alt mr-2" style="color: #ee5253"></i><?php echo $item['Adding_Date'];?></span>
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
                                    if(strlen($item['Description']) <= 75){

                                        echo $item['Description'];
                                    }else{
                                        $sub = substr($item['Description'] , 0 , 67);
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
                }?>
</div>
</div>
<?php
 include $tmp . 'footer.php'; ?>
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
    .carousel-item::after{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,.6);
        z-index: 1
    }

    .carousel-control-prev,
    .carousel-control-next{
        z-index: 10
    }

    .carousel-indicators li.active{
        background-color: #ee5253
    }
    .list-group{
        z-index: 0;

    }

    .catLi{
        z-index: 2
    }
    .catLi::before{
        content: '';
        position: absolute;
        left: -100%;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #ee5253;
        z-index: -1;
        transition: all .3s ease-out;

    }
    .catLi:hover::before{
        left: 0;
    }
    .catLi:hover a{
        color: #FFF !important
    }

    .list-group li:last-of-type a{
        border: 0 !important
    }

    .carousel-item ul:first-of-type li:hover{
        transform: translateX(0) !important;
    }

    .card.item{
        transition: all .1s ease-in-out;
    }

    .card.item:hover{
        transform: translateY(-4px);
        box-shadow: 0px 5px 10px 10px #DDD;
    }
</style>
