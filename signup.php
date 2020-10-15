<?php include 'init.php';

    // Generate Captcha String
    $random = substr(md5(mt_rand()), 0, 7);


    // Check If User Come With HTTP Request (1)
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // If The Request Is signup
            if(isset($_POST['signup'])){
                $userName = $_POST['userName'];
                $Pass = $_POST['userPass'];
                $pass2 = $_POST['userPass2'];
                $email = $_POST['userEmail'];
                $fullName = $_POST['fullName'];
                $hashPass = sha1($Pass); // For Password Security


                $captcha = $_POST['captcha'];
                $captchaCheck = $_POST['checkCaptcha'];

                // Check If Signup Inputs Values Is Valid
                $formErrors = array();

                // Check User Name
                if(isset($_POST['userName'])){
                    // Filter The userName For Prevent User From Enter Unpopular Tags In userName Input
                    $userName = filter_var($_POST['userName'] , FILTER_SANITIZE_STRING);

                    if(strlen($userName) <= 10){
                        $formErrors[] = 'user name Be 10 Characters Or More';
                    }
                }

                // Check User Password
                if(isset($_POST['userPass']) && isset($_POST['userPass2'])){
                    // Filter The userName For Prevent User From Enter Unpopular Tags In userName Input
                    $userPass1 = sha1($_POST['userPass']);
                    $userPass2 = sha1($_POST['userPass2']);

                    // Check If 2 Passwords Is Not Identical
                    if($userPass1 != $userPass2){
                        $formErrors[] = 'Passwords Not Match';
                    }


                    // Check If 2 Passwords Is Not Identical
                    // And I Check For Non Hashed Password As Empty Value Has A Value With sha1
                    if(empty($Pass) || empty($pass2)){
                        $formErrors[] = 'Passwords Must Be Fill';
                    }


                    if (strlen($Pass) <= '8') {
                        $formErrors[] = "Your Password Must Contain At Least 8 Digits !"."<br>";
                    }
                    elseif(!preg_match("#[0-9]+#",$Pass)) {
                        $formErrors[] = "Your Password Must Contain At Least 1 Number !"."<br>";
                    }
                    elseif(!preg_match("#[A-Z]+#",$Pass)) {
                        $formErrors[] = "Your Password Must Contain At Least 1 Capital Letter !"."<br>";
                    }
                    elseif(!preg_match("#[a-z]+#",$Pass)) {
                        $formErrors[] = "Your Password Must Contain At Least 1 Lowercase Letter !"."<br>";
                    }
                    elseif(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $Pass)) {
                        $formErrors[] = "Your Password Must Contain At Least 1 Special Character !"."<br>";
                    }
                }

                // Check User Email
                if(isset($_POST['userEmail'])){
                    // Filter The Email For Prevent User From Enter Unpopular Tags In user Email Input
                    $userEmail = filter_var($_POST['userEmail'] , FILTER_SANITIZE_EMAIL);

                    // Check If Email Valid
                    if(filter_var($_POST['userEmail'] , FILTER_VALIDATE_EMAIL) != true){
                        $formErrors[] = 'Sorry Email Is Not Valid';
                    }
                }

                // Check User fullName
                if(isset($_POST['fullName'])){
                    // Filter The userName For Prevent User From Enter Unpopular Tags In userName Input
                    $fullName = filter_var($_POST['fullName'] , FILTER_SANITIZE_STRING);

                    if(strlen($fullName) < 15){
                        $formErrors[] = 'user full Name Must Be 15 Characters Or More';
                    }
                }

                // Check For Captcha Validation
                if($captcha != $captchaCheck){
                    $formErrors[] = 'Sorry Captcha Is Not Valid';
                }

                // If There Is No SignUp Errors I Will Check UserName Duplications
                // Then Insert Data To DB
                if(empty($formErrors)){

                    $check = checkAny('user_Name','users',$userName);

                    if($check == 1){
                        echo '<div class="alert alert-danger w-75 mx-auto mt-3 shadow">Member Already Exist</div>';
                    }else{
                        // Update Data Depending On UserId
                        $stmt = $conn->prepare("
                            INSERT INTO users (user_Name,user_Password,user_Email,user_fullName,reg_status,reg_date)
                            VALUES(? , ? , ? , ? , 0 ,now())
                        ");
                        // Excute Query
                        $stmt->execute(Array($userName,$hashPass,$email,$fullName));

                        echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow">Member Added Successfully You Will Redirect To Login Page After 10 Seconds And You Should Wait For Admin Approve</div>';
                        header( "refresh:10;url=login.php" );
                    }

                }
                }else{

                }
            }

?>

    <div class="overlay position-absolute w-100"></div>
    <div class="container">
       <div class="formErrors">
            <?php
                if(!empty($formErrors)){
                    // Print All Signup Form Errors
                    foreach($formErrors as $err){
                        echo '<p class="alert alert-danger w-100 mx-auto mb-2 mt-3">'.$err.'</p>';
                    }
                }
            ?>
        </div>
        <form class="user-form p-4 w-50 mx-auto bg-white" action='<?php echo $_SERVER["PHP_SELF"]?>' method='POST'>
            <div class="d-flex justify-content-between">
                <div>
                    <i class="far fa-user fa-2x mr-2"></i><h2 class="mb-4 d-inline-block">Sign Up</h2>
                </div>
                <label class="mt-3 ml-2">
                    Login If Have Account <a href="login.php">Log In</a>
                </label>
            </div>
            <!-- User Name Validated From Front End By Using Pattern And Title -->
            <input type="text" name="userName" placeholder="username" autocomplete="off" class="form-control mt-2 rounded-pill" pattern=".{10,}" title="user Name Must Be 10 Characters Or More" required>
            <div class="d-flex align-items-center">
                <input type="password" name="userPass" placeholder="password" autocomplete="New-Password" style="width: 92%" class="form-control d-inline-block mt-3 rounded-pill" required>
            <div id="passNotes" class="rounded ml-auto mt-3 text-center text-white d-inline-block" style="clear: both; background-color: #343a40 !important"><i class="fa fa-plus" style="color: #ee5253"></i></div>

            </div>
            <div class="alert alert-info mt-2 notes p-0 border-0" style="height: 0; overflow: hidden">
                <b>Your Password Must Contain</b><br>
                (1) At Least 8 Digits<br>
                (2) At Least 1 Number<br>
                (3) At Least 1 Capital Letter<br>
                (4) At Least 1 Lowercase Letter<br>
                (5) At Least 1 Special Character<br>
            </div>
            <input type="password" name="userPass2" placeholder="Re-enter Password" autocomplete="New-Password" class="form-control mt-3 rounded-pill" required>
            <input type="text" name="fullName" placeholder="fullName" class="form-control mt-3 rounded-pill" pattern=".{15,}" title="user Full Name Must Be 15 Characters Or More" required>
            <input type="email" name="userEmail" placeholder="Email" class="form-control mt-3 rounded-pill" required>
            <label class="mt-3 pl-2 font-weight-bold">Check Recaptcha</label>
            <input type="text" name="captcha" id="re" style="font-size: 1.6em; font-family: 'Harlow Solid Italic'" class="form-control mt-1 rounded-pill" value="<?php  echo $random;?>" readonly />
            <input type="text" name="checkCaptcha" id="checkRe" class="form-control mt-3 rounded-pill"/>
            <input type="submit" name="signup" class="mt-4 btn mx-auto d-block text-white rounded-pill w-50" value="SignUp" required>
        </form>

    </div>


<style>
    body{
        background-image: url(layout/images/login.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        position: relative;
    }
    nav{
        display: none !important;
    }
    .overlay{
        top:-20px;
        left: 0;
        background-color: rgba(0,0,0,.6);
        z-index: -1;
        height: 109%;
    }

    .logout{
        display: none !important;
    }

    .notes{
        transition: all .3s linear;
    }
    #passNotes{
        width: 30px;
        cursor: pointer;
        height: 30px;
        line-height: 30px;
    }
    .toggleNotes{
        height: 154px !important;
        transition: all .3s linear;
        padding: 5px !important;
    }
</style>

<script>
     window.onload = function() {
         const myInput = document.getElementById('re');
         const myInput2 = document.getElementById('checkRe');

         // Prevent Copy And Cut And Paste Within Recaptcha Fields
         myInput.oncopy = function(e) {
           e.preventDefault();
         }

          myInput.oncut = function(e) {
           e.preventDefault();
         }

          myInput2.onpaste = function(e) {
           e.preventDefault();
         }

               $("#passNotes").click(function(){
      $(".notes").toggleClass("toggleNotes");
    });
     }


</script>
<?php include $tmp . 'footer.php'; ?>
