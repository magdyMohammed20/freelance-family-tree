<?php
    session_start();
    $pageTitle = 'Manage Members';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';
        // For Modify Query To Fetch The Pending Members
        $query = '';
        // Check The Query
        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        // Check If I Request Data Of Pending Members
        if(isset($_GET['pending'])== 'pend'){
            $query = 'AND reg_status = 0';
        }
        if($do == 'Manage'){

            $stmt = $conn->prepare("SELECT * FROM person");
            $stmt->execute();
            $members_data = $stmt->fetchAll();

        ?>
<h1 class="text-center mt-3">Manage Members</h1>
<div class="container">

                <?php
                        if(!$members_data){
                            echo '<div class="alert alert-danger"> No Users Found <span class="font-weight-bold">You Can Add New Users</span></div>';
                        }
                        else{
                            foreach($members_data as $member){
                 ?>
                 
                 <div class="card my-3">
                    <div class="card-header d-flex align-items-center bg-dark">
                    <i class="fas fa-address-book fa-2x mr-3" style="color: #ee5253"></i>
                    <div class='text-white'>
                        <?php echo $member['full_name']; ?> 
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Full Name: <?php echo $member['full_name'] ; ?>
                        </h5>
                        <h5 class="card-title">
                            Mother Name: <?php echo $member['mother_name'] ; ?>
                                
                        </h5>
                        <h5 class="card-title">
                            Wife Name: <?php echo $member['wife_name'] ; ?>
                        </h5>
                        <h5 class="card-title">
                            <?php 
                            
                            $stmtSons = $conn->prepare("SELECT * FROM person");
                            $stmtSons->execute();
                            $all = $stmtSons->fetchAll();
                            $sonsFound = true;

                            if($member['sons']){
                                if($sonsFound){
                                    echo 'Sons Names:';
                                    $sonsFound = false;
                                }
                                $sonsArr = explode(PHP_EOL, $member['sons']);
                                foreach($sonsArr as $son){
                                    
                                    echo "<div class='alert alert-danger w-75 mx-auto my-3 shadow'>".  $son ."</div>";
                                }

                             // Sons That Registered Before Parent
                             foreach($all as $son){
                                // Son Register Before
                                $sonBefore = explode(" ",$son['full_name']);
                                $memberArr = explode(" ",$member['full_name']);

                                // Parent Of The Before registered Son From Parent
                                $ParentName1 = strtolower($memberArr[0].' ' .$memberArr[1]);
                                
                                // Parent Of The Before registered Son From Son
                                $ParentName2 = strtolower($sonBefore[1].' '.$sonBefore[2]);

                                // Family Name From Parent 
                                $familyName1 = strtolower(end($memberArr));
                                // Family Name From Son 
                                $familyName2 = strtolower(end($sonBefore));


                                
                                // Check If Wife Is Son Of Speific Parent
                                if($ParentName1 === $ParentName2 && $familyName1 === $familyName2){
                                    
                                    
                                echo '<div class="alert alert-danger w-75 mx-auto my-3 shadow">'.$son['full_name'].'</div>';
                                    
                                }
                                
                             }

                             foreach($all as $sonThatWife){

                            
                                 // Wife Name Of User That Son For Another User
                                 $wif = explode(" ",$sonThatWife['wife_name']);
                                 $memberArr = explode(" ",$member['full_name']);

                                 // Parent Of The Wife From Parent
                                 $ParentName1 = strtolower($memberArr[0].' ' .$memberArr[1]);
                                 
                                 // Wife Parent Name From Wife
                                 $ParentName2 = strtolower($wif[1].' '.$wif[2]);

                                 // Family Name From Parent 
                                 $familyName1 = strtolower($memberArr[count($memberArr) - 1]);
                                 $familyName2 = strtolower($wif[count($wif) - 1]);
 
                                 
                                 // Check If Wife Is Son Of Speific Parent
                                 if($ParentName1 === $ParentName2 && $familyName1 === $familyName2){
                                     
                                     // For Prevent Sons Name Duplication If The Wife Entered By It's Parent
                                     $sonsArrForPreventDup = explode(PHP_EOL , $member['sons']);
                                     
                                     // If Wife Not Stored Previously With It's Parent We Will Store It
                                     if(array_search($sonThatWife['wife_name'] , $sonsArrForPreventDup) == "" || array_search($sonThatWife['wife_name'] , $sonsArrForPreventDup) < 0){
                                         echo '<div class="alert alert-danger w-75 mx-auto my-3 shadow">'.$sonThatWife['wife_name'].'</div>';
                                     }
                                 }
                                 
                                
                                
                            }
                              
                        }
                              
                
                    foreach($all as $member2){


                               // Wife Name Of User That Son For Another User
                                $wifeUser = explode(" ",$member2['wife_name']);
                                $memberArr = explode(" ",$member['full_name']);

                                // Parent Of The Wife From Parent
                                $ParentName1 = strtolower($memberArr[0].' ' .$memberArr[1]);
                                // Wife Parent Name From Wife
                                $ParentName2 = strtolower($wifeUser[1].' '.$wifeUser[2]);
                                // Family Name From Parent 
                                $familyName1 = strtolower($memberArr[count($memberArr) - 1]);
                                $familyName2 = strtolower($wifeUser[count($wifeUser) - 1]);

                                // Check If Wife Is Son Of Speific Parent
                                if($ParentName1 === $ParentName2 && $familyName1 === $familyName2){
                                    
                                    // For Prevent Sons Name Duplication If The Wife Entered By It's Parent
                                    $sonsArrForPreventDup = explode(PHP_EOL , $member['sons']);
                                    
                                    // If Wife Not Stored Previously With It's Parent We Will Store It
                                    if(array_search($member2['wife_name'] , $sonsArrForPreventDup) < 0 ){
                                        echo '<div class="alert alert-info">'.$member2['wife_name'].'</div>';
                                    }
                                }
                    }       
                              
                              
                              ?>
                                
                        </h5>
                        <h5 class="card-title">
                            <?php 
                            $brotherFound = 0;    
                            
                            

                // Get Categories Name
                $stmt = $conn->prepare("SELECT * FROM person");
                $stmt->execute();
                $all = $stmt->fetchAll();
                
                foreach($all as $member2){
                    // For Escape Output The Same Name As Brother
                    if($member2['full_name'] !== $member['full_name']){
                        $brotherArr = explode(" ",$member2['full_name']);
                        $memberArr = explode(" ",$member['full_name']);

                        if(strtolower($brotherArr[count($brotherArr) - 1]) === strtolower($memberArr[count($memberArr) - 1]) && strtolower($brotherArr[1]) === strtolower($memberArr[1])){
                            
                            if($brotherFound == 0){
                                $brotherFound = 1;
                                echo 'Brothers';
                            }
                             
                            echo '<div class="alert alert-success w-75 mx-auto my-3 shadow">'.$member2['full_name'].'</div>';
                        }
                    }
                }

                foreach($all as $member2){
                    // For Escape Output The Same Name As Brother                    
                    $sisterArr = explode(" ",$member2['wife_name']);
                    $memberArr = explode(" ",$member['full_name']);

                    if(strtolower(end($sisterArr)) === strtolower(end($memberArr)) && strtolower($sisterArr[1]) === strtolower($memberArr[1])){
                        $sliceSister = array_slice($sisterArr , 1);
                        if($brotherFound == 0){
                            $brotherFound = 1;
                            echo 'Brothers';
                        }
                        echo '<div class="alert alert-warning w-75 mx-auto my-3 shadow">'.implode(" " , $sisterArr).'</div>';
                    }
                }

                // Brother That Son For Previously Registered Parent
                foreach($all as $member2){
                    // For Escape Output The Same Name As Brother                    
                    $brotherArr = explode(PHP_EOL,$member2['sons']);
                    $memberArr = explode(" ",$member['full_name']);
                    $fatherName1 = $memberArr[1] . ' ' . $memberArr[2];
                    $memFamily = end($memberArr);

                    // Loop Through Son's For Get Brother's
                    foreach($brotherArr as $brother){
                        $expbrother = explode(" " , $brother);
                        $fatherName2 = $expbrother[1] . ' ' .$expbrother[2];
                        $familyName = end($expbrother);
                        
                        if($fatherName1 === $fatherName2 && $memFamily === $familyName){
                            if($brotherFound == 0){
                                $brotherFound = 1;
                                echo 'Brothers';
                            }
                            echo '<div class="alert alert-danger w-75 mx-auto my-3 shadow">'.implode(" " , $expbrother ).'</div>';
                        } 
                    }
                }

                              ?>
                        </h5>

                        <h5 class="card-title">
                            <?php 
                                $allUserName = explode(" " , $member['full_name']);
                                $uncle1Found = 0;
                                //echo 'sss'.$allUserName[count($allUserName) - 1] . '<br>';

                                foreach($all as $uncle){
                                    $filter = explode(" " , $uncle['full_name']);
                                    
                                    $memFamily = strtolower(end($allUserName));
                                    $uncleFamily = strtolower(end($filter));

                                    // If Uncle And Member Has Same Family Name And Member Not Equal To
                                    //$expmem = explode(" ",$member['full_name']);
                                    $slicemem = array_slice($allUserName,1);

                                    if( $memFamily == $uncleFamily && $member['full_name'] !== $uncle['full_name']){
                                        
                                        $expun= explode(" ",$uncle['full_name']);                                        

                                        // Filter Father From Being Uncle
                                        $sliceun2 = array_slice($expun,0);

                                        if(array_diff($slicemem , $sliceun2) !== Array()){
                                            if($uncle1Found == 0){
                                                $brotherFound = 1;
                                                echo 'Uncle: ';
                                            }
                                            echo '<div class="alert alert-info w-75 mx-auto my-3 shadow">'.implode(" " , $sliceun2).'</div>';
                                        }
                                    }
                                }
                            
                            ?>
                        </h5>
                        <h5 class="card-title">
                            <?php 
                            
                            $expmother = explode(" " , $member['mother_name']);
                            $fatherName1 = $expmother[1] . ' ' . $expmother[2];
                            $familyName1 = end($expmother);
                            $uncle2Found = 0;
                            //echo $fatherName1 .' '.$familyName1. '<br>'; 
                            foreach($all as $uncle){
                                $expfather = explode(" " , $uncle['full_name']);
                                $fatherName2 = $expfather[1] . ' ' . $expfather[2];
                                $familyName2 = end($expfather);
                                
                        
                                
                                if($fatherName1 === $fatherName2 && $familyName1 === $familyName2){
                                    if($uncle2Found == 0){
                                        $uncle2Found = 1;
                                        echo 'uncle(الخال):';
                                    }
                                    echo '<div class="alert alert-dark w-75 mx-auto my-3 shadow">'.$uncle['full_name'].'</div>';
                                }
                            }
                            
                            ?>
                        </h5>
                        <h5 class="card-title">
                            City Name: <?php echo $member['city_name'] ; ?>
                        </h5>
                    </div>
                </div>
                 <?php

                        }}
                ?>

<a href="members.php?do=Add" class="btn btn-primary mt-4">
        Add New Member
        <i class="fa fa-plus ml-1"></i>
    </a>
    </div>


<?php }
        else if($do == 'Add'){?>

<div class="container">
<h2 class="p-2 bg-dark text-white my-5 rounded"><i class="fas fa-user-plus mr-2" style="color: #ee5253"></i>Add New Member</h2>
    <form class="edit-form" action="?do=insert" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group my-3 col-6">
                <label class="mt-1 p-0">Full Name</label>
                <input type="text" name="fullName" class="form-control rounded-0" required>
            </div>
            <div class="form-group my-3 col-6">
                <label class="mt-1 p-0">Mother Name</label>
                <input type="text" name="motherName" class="form-control rounded-0" required>
            </div>
            <div class="form-group my-3 col-6">
                <label class="mt-1 p-0">Wife Name</label>
                <input type="text" name="wife" class="form-control rounded-0" required>
            </div>
            <div class="form-group my-3 col-6">
                <label class="mt-1 p-0">City Name</label>
                <input type="text" name="city" class="form-control rounded-0" required>
            </div>
            <div class="form-group my-3 col-12">
                <label class="mt-1 p-0">Son's Names</label>
                <textarea type="text" name="sons" style="resize: vertical" class="form-control rounded-0" required></textarea>
            </div>
            
            
        </div>
        <button class="btn text-white bg-dark p-2 mt-3 rounded-0"><i class="fas fa-plus mr-2" style="color: #ee5253"></i>Add New Member</button>
    </form>
</div>
<?php
}
        else if($do == 'insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                // Validate Inserted User Data
                // Array For Store Errors And Display It
                $formErrors = Array();


                $fullName = trim(strtolower($_POST['fullName']));
                $wife = trim(strtolower($_POST['wife']));
                $sons = trim(strtolower($_POST['sons']));
                $mother = trim(strtolower($_POST['motherName']));
                $city = trim(strtolower($_POST['city']));

                // For Check Duplication Of User name Before Insert
                //checkAny('user_Name', 'users' , $userName);

                if(empty($fullName) || $fullName === ''){
                    // Append Message To Array
                    $formErrors[] = "User Full Name Can't Be Empty";
                }

                if(empty($mother) || $mother === ''){
                    // Append Message To Array
                    $formErrors[] = "Mother Name Can't Be Empty";
                }

                if(empty($wife) || $wife === ''){
                    $formErrors[] = "User Wife Can't Be Empty";
                }

                if(empty($sons) || $sons === ''){
                    $formErrors[] = "User Sons Can't Be Empty";
                }

                if(empty($city) || $city === ''){
                    $formErrors[] = "User City Can't Be Empty";
                }
                // If There Aren't Errors Update The DB
                if(count($formErrors) > 0){

                    echo '<div class="container mt-5">';
                    // Display All Errors For User
                    foreach($formErrors as $error){
                        echo "<div class='alert alert-danger w-75 mx-auto my-3 shadow-sm rounded-0'>".  $error ."</div>";
                    }

                    echo '<div class="w-75 mx-auto mt-4"><a href="?do=Add" class="d-inline-block bg-dark text-white p-3 text-decoration-none"><i class="fas fa-chevron-left mr-2" style="color: #ee5253;"></i>Back To Add Member Page</a></div>';
                    echo '</div>';
                }else{

                    // Update Data Depending On UserId
                    $stmt = $conn->prepare("
                        INSERT INTO person (full_name,mother_name, wife_name , sons , city_name)
                        VALUES(? , ? , ? , ? ,?)
                    ");
                    // Excute Query
                    $stmt->execute(Array($fullName,$mother,$wife,$sons,$city));
                    
                    echo '<div class="container mt-5">';
                        echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow-sm rounded-0">Member Added Successfully</div>';
                        echo '<div class="w-75 mx-auto mt-4"><a href="?do=Add" class="d-inline-block bg-dark text-white p-3 text-decoration-none"><i class="fas fa-chevron-left mr-2" style="color: #ee5253;"></i>Add New Member</a></div>';
                    echo '</div>';
                }

        }else{
            redirectHome('You Can\'t Access This Page Directly',10);
        }
    }
        else if($do == 'Edit'){
            // Check For User_Id That Want To Update The Data
            $userId = isset($_GET['user_Id']) && is_numeric($_GET['user_Id'])?intval($_GET['user_Id']):0;

            // Select Data Depending On UserId
            $stmt = $conn->prepare("
                SELECT *
                FROM users
                WHERE user_Id=?
                LIMIT 1"
            );
            // Excute Query
            $stmt->execute(Array($userId));
            // Fetch Data
            $data = $stmt->fetch(); // Fetch Row Data As Array

            // Check If Row Exist
            if($stmt->rowCount() > 0){

            ?>
<!-- If Row Exist Show The Form Of Update The Data -->
<h2 class="text-center my-3">Edit Members</h2>
<div class="container">
    <form class="edit-form" action="?do=Update" method="POST">
        <input type="hidden" name='userId' value="<?php echo $userId?>">
        <div class="form-group my-3">
            <label class="mt-1 p-0">userName</label>
            <input type="text" name="userName" class="form-control" autocomplete="off" value="<?php echo $data['user_Name']; ?>">
        </div>
        <div class="form-group my-3">
            <label class="mt-1 p-0">password</label>
            <input type="hidden" name="oldPass" value="<?php echo $data['user_Password'];?>">
            <input type="password" name="password" class="form-control" autocomplete="new-password" value="">
        </div>
        <div class="form-group my-3">
            <label class="mt-1 p-0">email</label>
            <input type="text" name="email" class="form-control" value="<?php echo $data['user_Email'];?>">
        </div>
        <div class="form-group my-3">
            <label class="mt-1 p-0">fullName</label>
            <input type="text" name="fullName" class="form-control" value="<?php echo $data['user_fullName'];?>">
        </div>
        <button class="btn btn-primary col-3 mt-3">Save</button>
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
                echo '<h1 class="text-center">Update Member</h1>';
                $id = $_POST['userId'];
                $userName = $_POST['userName'];
                $email = $_POST['email'];
                $fullName = $_POST['fullName'];

                // Check For User name Duplication Before Update The Data
                checkAny('user_Name', 'users' , $userName);

                // Check If Password Is Setted I Will Update Password In DB
                // Else I Will Updated Other Data With Old Password
                $newPassword = empty($_POST['password']) ? $_POST['oldPass'] : sha1($_POST['password']);

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

                    // Print Number Of Updated Records
                    echo '<h1 class="alert alert-success w-75 mx-auto text-center">' . $stmt->rowCount() . ' Record Updated' . '</h1>';
                }

            }else{
                redirectHome('You Can\'t Access This Page Directly',5);
            }
        }

        else if($do == 'Delete'){
            // Check For User_Id That Want To Update The Data
            $userId = isset($_GET['user_Id']) && is_numeric($_GET['user_Id'])?intval($_GET['user_Id']):0;

            // Select Data Depending On UserId
            $stmt = $conn->prepare("
                DELETE
                FROM users
                WHERE user_Id=?
                LIMIT 1"
            );
            // Excute Query
            $stmt->execute(Array($userId));

            if($stmt->rowCount() > 0){
                echo '<div class="alert alert-success w-75 mx-auto mt-4">Member Deleted Successfully</div>';
            }else{
                redirectHome('Member Not Founded' , 10);
            }
        }
        else if($do == 'activate'){
            // Check For User_Id That Want To Update The Data
            $userId = isset($_GET['user_Id']) && is_numeric($_GET['user_Id'])?intval($_GET['user_Id']):0;

            // Check If User Id Is Exist In DB Or Not
            $check = checkActivated('user_Id','users',$userId);

            // If User Id Exist Then Update User reg_status To Be A Member
            if($check == true){
                // Select Data Depending On UserId
                $stmt = $conn->prepare("
                    UPDATE users
                    SET reg_status = 1
                    WHERE user_Id=?
                    LIMIT 1"
                );
                // Excute Query
                $stmt->execute(Array($userId));

                echo '<div class="alert alert-success w-75 mx-auto mt-4">Record Updated Successfully And User Be A Member</div>';
            }
            // If User Id Not Exist Then Show Error Message
            else{
                echo '<div class="alert alert-danger w-75 mx-auto mt-4">Failed To Update User As A Member</div>';
            }
        }
        //include $tmp . 'footer.php';

    }else{
        header('Location: index.php');
        exit();
    }
