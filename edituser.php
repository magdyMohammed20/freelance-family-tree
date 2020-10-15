<?php

    session_start();
    $pageTitle = 'Edit Data';
    include 'init.php';
// Check If User Login I Will Show All Data And Profile Else Will Direct Me To Login Page
    if($sessionUser){
        $did = isset($_GET['did'])?$_GET['did']:'';

        // Get All User Data To Set It In The Profile
        $getUser = $conn->prepare('SELECT * FROM users WHERE user_Name = ?');

        $getUser->execute(array($_POST['userName']));

        $info = $getUser->fetch();
        echo '<h1 class="text-center mt-4">Edit Data</h1>';
        if($did == 'Update'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $id = $_POST['user_Id'];
                $userName = $_POST['userName'];
                $email = $_POST['email'];
                $fullName = $_POST['fullName'];


                // Check For User name Duplication Before Update The Data
                checkAny('user_Name', 'users' , $userName);

                // Check If Password Is Setted I Will Update Password In DB
                // Else I Will Updated Other Data With Old Password
                $newPassword = empty($_POST['Newpassword']) ? $_POST['oldPass'] : sha1($_POST['Newpassword']);

                // Validate Updated User Data
                // Array For Store Errors And Display It
                $formErrors = Array();
                if(empty($userName)){
                    // Append Message To Array
                    $formErrors[] = "User Name Can't Be Empty";
                }
                if(empty($email)){
                    // Append Message To Array
                    $formErrors[] = "User Email Can't Be Empty";
                }
                if(empty($fullName)){
                    // Append Message To Array
                    $formErrors[] = "User Full Name Can't Be Empty";
                }


                // If There Aren't Errors Update The DB
                if(count($formErrors) > 0){
                    // Display All Errors For User
                    foreach($formErrors as $error){
                        echo "<div class='alert alert-danger w-75 mx-auto my-3 shadow'>".  $error ."</div>";
                    }
                }else{
                    // Update Data Depending On UserId
                    $stmt = $conn->prepare("
                        UPDATE users
                        SET user_Name=?,user_Password=?,user_Email=?,user_fullName=?
                        WHERE user_Id=?
                        LIMIT 1"
                    );
                    // Excute Query
                    $stmt->execute(Array($userName,$newPassword,$email,$fullName,$id));


                    echo '<div class="alert alert-success w-75 mx-auto">
                        Your Data Will Be Updated Successfully You Should Logout And Login Again
                        <a href="logout.php">Logout</a>
                    </div>';

                }

            }else{
                redirectHome('You Can\'t Access This Page Directly',5);
            }
        }
?>
    <div class="container">

        <form class="edit-form" action="?did=Update" method="POST">

            <input type="hidden" name='user_Id' value="<?php echo $info['user_Id']?>">
            <div class="form-group my-3">
                <label class="mt-1 p-0">userName</label>
                <input type="text" name="userName" class="form-control" autocomplete="off" value="<?php echo $info['user_Name']; ?>">
            </div>
            <div class="form-group my-3">
                <label class="mt-1 p-0">New password</label>
                <input type="hidden" name="oldPass" value="<?php echo $info['user_Password'];?>">
                <input type="password" name="Newpassword" class="form-control" autocomplete="new-password" value="">
            </div>
            <div class="form-group my-3">
                <label class="mt-1 p-0">email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $info['user_Email'];?>">
            </div>
            <div class="form-group my-3">
                <label class="mt-1 p-0">fullName</label>
                <input type="text" name="fullName" class="form-control" value="<?php echo $info['user_fullName'];?>">
            </div>
            <button class="btn btn-primary col-3 mt-3">Save</button>
        </form>
    </div>
<?php } ?>
<style>
    .logo{
        margin-right: 5px !important
    }
    .loginASign {
        display: none !important;
    }
</style>
