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

        $userName = trim($_POST['user']);
        $adminPass = trim($_POST['adminPass']);
        

        // Check If User Exist In DB And Check If Admin Or Not (2)
        $stmt = $conn->prepare("
            SELECT id , name , password
            FROM admin
            WHERE name=?
            AND password=?
            LIMIT 1");
        $stmt->execute(Array($userName , $adminPass));
        $data = $stmt->fetch(); // Fetch Row Data As Array
        // Get Number Of Rows That Extracted From Query (3)
        $count = $stmt->rowCount();

        // Check If User Already Exist Or Not
        if($count != 0){
            // Get Name Of The Session And Fetch User Id As Session Id
            $_SESSION['userName'] = $userName;
            $_SESSION['user_Id'] = $data['id'];
            //$_SESSION['fullName'] = $data['user_fullName'];
            // Then Direct Me To Dashboard
            header('Location: dashboard.php');
            exit();


        }
        
    }

?>
    <!-- Div For Cover -->
    <div class="cover"></div>
    <!-- Admin Login Form -->
    <form class="admin-form w-50 p-5" action='<?php echo $_SERVER["PHP_SELF"]?>' method='POST'>
        <?php 
            
            if(isset($userName) && isset($adminPass) && ($userName === '' || $adminPass === '' ) && (isset($count) && $count == 0)){
                echo '<div class="alert alert-danger"> Invalid Admin Name Or Password </div>';
            }

        ?>
        <h2 class="mb-4">Admin Login</h2>
        <input type="text" name="user" placeholder="username" autocomplete="off" class="form-control mt-3 rounded-0">
        <input type="password" name="adminPass" placeholder="password" autocomplete="New-Password" class="form-control mt-3 rounded-0">
        <input type="submit" name="subAdmin" class="mt-4 btn text-white rounded-0 w-25 px-3 py-2" value="Login">
    </form>
    <style>
        body{
            background-image: url('./layout/images/admin-login/login.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .admin-form input[type='submit']{
            background-color: #ee5253
        }

        .admin-form input[type='submit']:hover{
            background-color: #222
        }
    </style>
<?php
    include $tmp . 'footer.php';
?>
