<?php
    session_start(); // Start Session
    $pageTitle = 'Admin Login';
    // For Allow Or Not Allow NavBar
    $noNav = '';
    // If Session Is Opened I Will Directed To Dashboard Without Login
    if(isset($_SESSION['userName'])){
        header('Location: dashboard.php');
    }

    include 'init.php';
    include $tmp . 'header.php';
    // Check If User Come With HTTP Request (1)
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $userName = $_POST['user'];
        $adminPass = $_POST['adminPass'];
        $hashPass = $adminPass; // For Password Security

        // Check If User Exist In DB And Check If Admin Or Not (2)
        $stmt = $conn->prepare("
            SELECT user_Id , user_Name , user_Password  ,     user_fullName
            FROM users
            WHERE user_Name=?
            AND user_Password=?
            AND group_Id=1
            LIMIT 1");
        $stmt->execute(Array($userName , $hashPass));
        $data = $stmt->fetch(); // Fetch Row Data As Array
        // Get Number Of Rows That Extracted From Query (3)
        $count = $stmt->rowCount();

        // Check If User Already Exist Or Not
        if($count != 0){
            // Get Name Of The Session And Fetch User Id As Session Id
            $_SESSION['userName'] = $userName;
            $_SESSION['user_Id'] = $data['user_Id'];
            $_SESSION['fullName'] = $data['user_fullName'];
            // Then Direct Me To Dashboard
            header('Location: dashboard.php');
            exit();


        }
    }

?>
    <!-- Div For Cover -->
    <div class="cover"></div>
    <!-- Admin Login Form -->
    <form class="admin-form p-4" action='<?php echo $_SERVER["PHP_SELF"]?>' method='POST'>
        <h2 class="mb-4">Admin Login</h2>
        <input type="text" name="user" placeholder="username" autocomplete="off" class="form-control mt-3 rounded-pill">
        <input type="password" name="adminPass" placeholder="password" autocomplete="New-Password" class="form-control mt-3 rounded-pill">
        <input type="submit" name="subAdmin" class="mt-4 btn mx-auto d-block text-white rounded-pill w-50" value="Login">
    </form>
    <style>
        body{
            background-image: url(layout/images/admin-login/login.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .admin-form input[type='submit']{
            background-color: #ee5253
        }
    </style>
<?php
    include $tmp . 'footer.php';
?>
