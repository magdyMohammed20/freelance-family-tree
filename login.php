<?php
    session_start();
    $pageTitle = 'User Login';
    if(isset($_SESSION['user'])){
        header('Location: index.php');
    }
        include 'init.php';

    // Check If User Come With HTTP Request (1)
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // If The Request Is Login
            if(isset($_POST['login'])){
                $userName = $_POST['userName'];
                $Pass = $_POST['password'];
                $hashPass = sha1($Pass); // For Password Security

                // Check If User Exist In DB And Check If Admin Or Not (2)
                // And Get user_Id For Session
                $stmt = $conn->prepare("
                    SELECT  user_Id,user_Name , user_Password
                    FROM users
                    WHERE user_Name=?
                    AND user_Password=?
                    ");
                $stmt->execute(Array($userName , $hashPass));
                $data = $stmt->fetch(); // Fetch Row Data As Array
                // Get Number Of Rows That Extracted From Query (3)
                $count = $stmt->rowCount();

                // Check If User Already Exist Or Not
                if($count != 0){
                    // Get Name Of The Session And Fetch User Id As Session Id
                    $_SESSION['user'] = $userName;

                    // Get User Id For Session
                    $_SESSION['userId'] = $data['user_Id'];

                    // Then Direct Me To profile
                    header('Location: profile.php');
                    exit();


                }
            }
        }

?>
    <div class="overlay position-absolute w-100 h-100"></div>
    <div class="position-absolute d-flex align-items-center" style='top: 30px; left: 5px;'>
        <img src="layout/images/logo.png" style="width: 50px; height: 50px"/>
        <h3 class="text-white ml-2">E-Commerce <span style="color: #ee5253">Shop</span></h3>
        <a class="stretched-link" href="index.php"></a>
    </div>
    <div class="container position-relative">

        <form class="user-form p-4 w-50 mx-auto bg-white" style="margin-top: 150px" action='<?php echo $_SERVER["PHP_SELF"]?>' method='POST'>
            <i class="far fa-user fa-2x mr-2"></i><h2 class="mb-4 d-inline-block">Login</h2>
            <input type="text" name="userName" placeholder="username" autocomplete="off" class="form-control mt-2 rounded-pill">
            <input type="password" name="password" placeholder="password" autocomplete="New-Password" class="form-control mt-3 rounded-pill">
            <label class="mt-3 ml-2">
                Sign Up If No Account <a href="signup.php">Sign Up</a>
            </label>
            <input type="submit" name="login" class="mt-4 btn mx-auto d-block text-white rounded-pill w-50" value="Login">
        </form>
    </div>


<style>
    body{
        background-image: url(layout/images/login.jpg);
        background-size: cover;
        background-repeat: no-repeat;
    }
    nav{
        display: none !important;
    }
    .overlay{
        top:0;
        left: 0;
        background-color: rgba(0,0,0,.6);
        z-index: -1;
    }
    .logout{
        display: none !important;
    }
</style>
<?php include $tmp . 'footer.php'; ?>
