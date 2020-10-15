<?php
    session_start();
    $pageTitle = 'Manage Categories';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        if($do == 'Manage'){
            echo '<h1 class="text-center my-3">Manage Categories</h1>';

            // Make Variable And Array To Control On Categories Arrange Methods
            $sort = 'ASC';
            $sort_Methods = array('ASC','DESC');

            // Check If sort Methods Is Requested And In Array
            if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_Methods)){
                $sort = $_GET['sort'];
            }

            // Set Order Methods [$sort]
            $stmt = $conn->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll();

            ?>


    <div class="container admin-stats2">
        <div class="row">
            <div class="col-12 p-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>
                            <i class="fas fa-columns mr-2 fa-lg"></i>All Categories
                        </span>
                        <span class="ordering-method">
                            Ordering Method:
                            <!-- If Order Is ASC Or DESC Set [active] Class For Badge -->
                            <a class="badge badge-primary p-2 <?php if($sort=='ASC'){echo ' active';}?>" href="?sort=ASC">Ascending</a>
                            <a class="badge badge-primary p-2 <?php if($sort=='DESC'){echo ' active';}?>" href="?sort=DESC">Descending</a>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <?php
                                    echo '<ul class="cats list-unstyled p-0 mb-0">';
                                    foreach($cats as $cat){
                                        echo '<li class="p-3  m-0 d-flex align-items-center justify-content-between">';

                                        echo '<div>';
                                        echo '<h2 class="d-block my-2 font-weight-bold">'.$cat['Name'].'</h2>';

                                        echo '<span class="my-2 d-block text-muted">';

                                            // If Category Don't Have Description Print Message
                                            if($cat["Description"] == ""){
                                                echo "This Category Don't Has Description";
                                            }else{
                                                echo $cat["Description"];
                                            }
                                        echo '</span>';

                                        //echo '<span class=" my-2 d-block">Ordering : ' . $cat['Ordering'].'</span>';
                                        echo '<div class="cat-features mt-3">';

                                        echo '</div>';

                                        echo '</div>';

                                        echo '<div class="cat-controls">';
                                        echo '<a href="categories.php?do=Edit&catid='.$cat['ID']. '" class="p-1 mr-2 btn btn-primary p-1 text-white">Edit <i class="far fa-edit fa-sm"></i></a>';
                                        echo '<a href="categories.php?do=Delete&catid='.$cat['ID']. '" class="p-1 mr-2 btn btn-danger p-1 text-white">Delete <i class="far fa-trash-alt fa-sm"></i></a>';
                                        echo '</div>';
                                        echo '</li> <hr>';
                                    }
                                    echo '</ul>';
                                ?>
    <a href="?do=Add" class="btn text-primary my-2 ml-2">Add New Category <i class="fa fa-plus fa-sm"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
        }
        else if($do == 'Add'){

            ?>
<h2 class="text-center my-3">Add New Category</h2>
<div class="container">
    <form class="edit-form" action="?do=insert" method="POST">
        <div class="form-group my-3">
            <label class="mt-1 p-0">Category Name</label>
            <input type="text" name="Name" class="form-control" required='required' autocomplete='off'>
        </div>
        <div class="form-group my-3">
            <label class="mt-1 p-0">Category Description</label>
            <input type="text" name="Description" class="form-control">
        </div>
        <div class="form-group my-3">
            <label class="mt-1 p-0">Category Ordering</label>
            <input type="text" name="Ordering" class="form-control">
        </div>

        <button class="btn btn-primary mt-3">Add Category <i class="fa fa-plus ml-1"></i></button>
    </form>
</div>
<?php
        }
        else if($do == 'insert'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo '<h1 class="text-center mt-4">Add New Category</h1>';

                $Name = $_POST['Name'];
                $Desc = $_POST['Description'];
                $Ordering = $_POST['Ordering'];

                // For Check Duplication Of Categories Before Insert
                $check = checkItems('Name', 'categories' , $Name);

                if($check > 0){
                    // Redirect To Admin Home Page After 5 Seconds
                    redirectHome('Category Name Already Exist' , 5);
                    exit();
                }else{
                    // Insert Data
                    $stmt = $conn->prepare("
                        INSERT INTO categories (Name,Description,Ordering)
                        VALUES(? , ? , ?)
                    ");
                    // Excute Query
                    $stmt->execute(Array($Name,$Desc,$Ordering));

                    echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow">Member Added Successfully</div>';
                }

        }
        }
        else if($do == 'Edit'){

            // Check For Cat_Id That Want To Update The Data
            $catId = isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0;

            // Select Data Depending On UserId
            $stmt = $conn->prepare("
                SELECT *
                FROM categories
                WHERE ID=?
                "
            );
            // Excute Query
            $stmt->execute(Array($catId));
            // Fetch Data
            $data = $stmt->fetch(); // Fetch Row Data As Array

            // Check If Row Exist
            if($stmt->rowCount() > 0){

            ?>
            <!-- If Row Exist Show The Form Of Update The Data -->
            <h1 class="text-center my-3">Edit Category</h1>
            <div class="container">
                <form class="edit-form" action="?do=Update" method="POST">
                    <input type="hidden" name='catid' value="<?php echo $_GET['catid']; ?>">
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Category Name</label>
                        <input type="text" name="catName" class="form-control" autocomplete="off" value="<?php echo $data['Name'];?>">
                    </div>
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Category Description</label>
                        <input type="text" name="catDesc" class="form-control" autocomplete="new-password" value="<?php echo $data['Description'];?>">
                    </div>
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Category Ordering</label>
                        <input type="text" name="catOrder" class="form-control" value="<?php echo $data['Ordering'];?>">
                    </div>

                    <button class="btn btn-primary mt-3 px-5">Save</button>
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
                echo '<h1 class="text-center my-4">Update Category</h1>';
                $id = $_POST['catid'];
                $Name = $_POST['catName'];
                $Desc = $_POST['catDesc'];
                $Order = $_POST['catOrder'];




                // Update Data Depending On UserId
                $stmt = $conn->prepare("
                    UPDATE categories
                    SET Name=?,Description=?,Ordering=?
                    WHERE ID=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($Name,$Desc,$Order,$id));

                // Print Number Of Updated Records
                echo '<div class="alert alert-success w-75 mx-auto text-center">' . $stmt->rowCount() . ' Record Updated' . '</div>';


            }else{
                redirectHome('You Can\'t Access This Page Directly',5);
            }
        }

        else if($do == 'Delete'){
            // Check For category id That Want To Update The Data
            $catId = isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0;

            // Check If Category Id Is Exist In DB Or Not
            $check = checkItems('ID','categories',$catId);

            if($check > 0){
                // Select Data Depending On UserId
                $stmt = $conn->prepare("
                    DELETE
                    FROM categories
                    WHERE ID=?
                    LIMIT 1"
                );


                // Excute Query
                $stmt->execute(Array($catId));



                echo '<div class="alert alert-success w-75 mx-auto mt-4">Category Deleted Successfully</div>';
                }else{
                    redirectHome('Category Not Founded' , 10);
                }
            }

        //include $tmp . 'footer.php';

    }else{
        header('Location: index.php');
        exit();
    }
