<?php
    // This Page For Show Item Data When User Click On The Item
    session_start();
    $pageTitle = 'Items';
    include 'init.php';

    if(!isset($_SESSION['user'])){
        header('Location: login.php');
    }
    // Check For Cat_Id That Want To Update The Data
    $itemId = isset($_GET['item_Id']) && is_numeric($_GET['item_Id'])?intval($_GET['item_Id']):0;


    // Select Data Depending On UserId
    $stmt = $conn->prepare("
        SELECT items.*,categories.Name AS catName,users.user_Name
        FROM items
        INNER JOIN categories
        ON categories.ID=items.Cat_Id
        INNER JOIN users
        ON users.user_Id = items.Member_Id
        WHERE item_Id=?
        "
    );
    // Excute Query
    $stmt->execute(Array($itemId));
    // Fetch Data
    $data = $stmt->fetch(); // Fetch Row Data As Array


    // If The User Write Un Existed Id In Address Will Display Error
    if(empty($data)){
        echo "<div class='alert alert-danger mt-4 w-75 mx-auto'>Sorry Item Not Founded</div>";
    }else{
        $itemImg = $data['Image'];

    }

?>

<div class="container mt-5">
   <!-- Display Item Data -->
    <div class="row">
        <div class="col-3 position-relative overflow-hidden img-container">
            <img src="<?php echo $itemImg; ?>" alt='Item' class='w-100 h-100'/>
            <span class="itemPrice position-absolute rounded bg-dark text-white p-3 mt-1 font-weight-bold h5"><?php echo $data['Price'];?></span>
        </div>
        <div class="col-8">
            <p class="text-muted bg-white p-3 position-relative">
                <span class='position-absolute catName'><label class='badge  badge-warning'><a href="categories.php?pageid=<?php echo $data['Cat_Id']."&pagename=".$data['catName'];?>"><i class="fas fa-sitemap mr-2"></i><?php echo $data['catName'];?></a></label></span>
                <span class='position-absolute itemName'><label class='badge  badge-warning'><i class="fab fa-product-hunt mr-2"></i><?php echo $data['Name'];?></label></span>
                <?php echo $data['Description'];?>
            </p>
            <span class="text-muted" style="font-style:italic"><i class="fas fa-user mr-2"></i><?php echo $data['user_Name'];?></span>
            <span class="ml-3 text-muted" style="font-style:italic"><i class="far fa-calendar-alt mr-2"></i><?php echo $data['Adding_Date'];?></span>
            <span class="ml-3 text-muted" style="font-style:italic"><i class="fas fa-globe-americas mr-2"></i><?php echo $data['Country_Made'];?></span>
            <!-- For Send Customer Items -->
            <form method="post" action="cart.php">
                <input type="hidden" value="<?php echo $data['item_Id'];?>" name="itemId"/>
                <input type="hidden" value="<?php echo $data['Name'];?>" name="itemName"/>
                <input type="hidden" value="<?php echo $data['Price'];?>" name="itemPrice"/>
                <input type="hidden" value="<?php echo $sessionUser;?>" name="itemCustomer"/>
                <input type="hidden" value="<?php echo $data['user_Name'];?>" name="itemSeller"/>
                <?php
                    if($_SESSION['user'] != $data['user_Name']){
                        echo '<input type="submit" class="btn btn-primary d-block w-25 mt-3" value="Add To Cart"/>';
                    }

                ?>

            </form>
        </div>
    </div>
    <!-- Item Comments -->
    <?php
        if(isset($_SESSION['user'])){
    ?>
    <div>
        <div class="row">
            <div class="add-comment col-12">
                <h3 class="bg-dark text-white p-2 mt-4"><i class="far fa-comment fa-lg m-2 " style="color:#ee5253"></i>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF']."?item_Id=".$data['item_Id'];?>" method="post">
                    <textarea class="w-100 mt-3" name="addComment" required></textarea>
                    <input type="submit" value='Add Comment' class='btn btn-primary mt-2'/>
                </form>
                <?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        // If User Add Comment I Will Get This Data To Insert Comment In DB
                        $comment = filter_var($_POST['addComment'],FILTER_SANITIZE_STRING);
                        $userId = $_SESSION['userId']; // For Get Name Of Comment Writer
                        $itemId=$data['item_Id'];

                        // If Comment Not Empty Insert The Comment To DB
                        if(!empty($comment)){
                            $stmt = $conn->prepare('
                                INSERT INTO comments(comment,status,comment_date,item_id,user_id)
                                VALUES(?,0,NOW(),?,?)
                            ');

                            $stmt->execute(array($comment,$itemId,$userId));

                            // If Comment Inserted To DB Display Success Message
                            if($stmt){
                                echo '<div class="alert alert-success w-100 mx-auto mt-3">Comment Added Successfully</div>';

                            }
                        }else{
                            echo '<div class="alert alert-danger w-100 mx-auto mt-3">You Should Type Comment</div>';
                        }
                    }
                ?>
            </div>
            <!-- Show All Comments For Item -->
            <div class="col-12">
                <div class="row">

                    <div class='col-12'>

                        <?php
                            // Join Comments Table With users Tables For Get Activated Comments
                            $stmt = $conn->prepare("SELECT comments.*,users.user_Name,users.image
                                                    FROM comments
                                                    INNER JOIN users
                                                    ON users.user_Id = comments.user_id
                                                    WHERE item_id = ?
                                                    AND status = 1
                                                    ORDER BY c_id DESC");
                            $stmt->execute(array($data['item_Id']));
                            $comments = $stmt->fetchAll();
                            foreach($comments as $comment){

                                    ?>
                                <div class="row mt-5">
                                    <div class="col-2 text-center">
                                        <img src="admin/layout/images/<?php echo $comment['image'];?>" class='rounded-pill' style="width: 50px; height: 50px;"/>
                                        <span class="d-block CommentPublisher text-white rounded mt-2 px-1"><?php echo $comment['user_Name'];?></span>
                                    </div>
                                    <div class="col-10">
                                        <p class="comment rounded text-white p-3 position-relative">
                                            <?php echo $comment['comment'];?>

                                        </p>
                                        <div class="col-3 text-white commentDate">
                                            <i class="far fa-calendar-alt mr-2"></i>
                                            <span><?php echo $comment['comment_date'];?></span>
                                        </div>
                                    </div>
                                    <hr class="w-100 ">
                                </div>
                                    <?php


                                $status = $comment['status'];
                                $userId = $comment['user_id'];


                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } else{
            echo '<div class="alert alert-info w-75 mx-auto mt-3">Login Or Register To Add Comment <a class="logASign py-3" href="login.php">Login </a></div>';
        }

    ?>
</div>

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
    .catName{
        top: -12px;
        left: 10px;
    }
    .itemPrice{
        top: 0;
        left: 0
    }
    .itemName{
        top: -12px;
        left: 110px;
    }
    .comment{
        background-color: #343a40 !important;
    }

    .comment::after{
        content: '';
        position: absolute;
        left: -20px;
        top: 50%;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        border-right-color: #343a40;
        transform: translateY(-50%);
    }
    .commentDate i{
        color: #ee5253
    }
    .commentDate span{
        color:#343a40;
        font-style: italic
    }
    .CommentPublisher{
        background-color: #343a40;
    }

</style>
