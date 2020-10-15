<?php
    session_start();
    $pageTitle = 'Search For Members';
     // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

        
        if (!isset($GET['do'])){

            $stmt = $conn->prepare("SELECT * FROM person");
            $stmt->execute();
            $members_data = $stmt->fetchAll();
?>


<div class="container admin-stats py-4">
<button class="w3-button w3-teal w3-xlarge w3-left w3-open position-absolute" onclick="openLeftMenu()" style='left: 0; '>&#9776;</button>
    
    <div class="row mt-4">
        
        <form method='POST' action='?do=find'>
            <div class='row'>
                <div class='p-2 col-5 m-2'>
                    <label>First Name</label>
                    <input type='text' class='form-control ' name='first_name_search' required/>
                </div>
                <div class='p-2 col-5 m-2'>
                    <label>Second Name</label>
                    <input type='text' class='form-control' name='second_name_search' required/>
                </div>
                <div class='p-2 col-5 m-2'>
                    <label>Third Name</label>
                    <input type='text' class='form-control' name='third_name_search' required/>
                </div>
                <div class='p-2 col-5 m-2'>
                    <label>Family Name</label>
                    <input type='text' class='form-control' name='family' required />
                </div>
                <div class='p-2 col-5 m-2'>
                    <label>City Name</label>
                    <input type='text' class='form-control' name='city_search' required/>
                </div>
                
            </div>
            <input type='submit' class='btn btn-primary ml-3' value='Search'/>
        </form>
        
    </div>
</div>

<!-- End Dashboard Content -->
<?php
        include $tmp . 'footer.php';
    }

    if(isset($_GET['do']) && $_GET['do'] == 'find'){
    
        $firstName = trim(strtolower($_POST['first_name_search']));
        $middleName = trim(strtolower($_POST['second_name_search'])) .' '. trim(strtolower($_POST['third_name_search']));
        $family = trim(strtolower($_POST['family']));
        $city = trim(strtolower($_POST['city_search']));
        $personFounded = '';
        $personFoundedMother = '';
        $personFoundedSons = '';

         // Check If User Exist In DB And Check If Admin Or Not (2)
         $stmt2 = $conn->prepare("SELECT * FROM person");

        $stmt->execute();
        $data = $stmt->fetchAll();
        
        foreach($data as $name){
            $expName = explode(" " , $name['full_name']);
            $cityName = $name['city_name'];
            $midName = $expName[1] . ' ' . $expName[2];
            if($firstName === $expName[0] && $middleName === $midName && $city === $cityName){
                echo 'Person Name ' . $name['full_name'] . '<br><br>';
                echo 'Wife Name ' . $name['wife_name'] . '<br><br>';
                echo 'Mother Name ' . $name['mother_name'] . '<br><br>';
                echo 'City Name ' . $name['city_name'] . '<br><br>';
                $personFounded = $name['full_name'];
                $personFoundedMother = $name['mother_name'];
                $personFoundedSons = $name['sons'];
            }
        }


            // Get Sons Of Users
            $sonsArr = explode(PHP_EOL, $personFoundedSons);
            echo 'Sons:' . '<br>';
            foreach($sonsArr as $son){
                echo "<div class='alert alert-danger w-75 mx-auto my-3 shadow'>".  $son ."</div>";
            }

            // Find Son That Wife Of Another User
            foreach($data as $son){
                $expBrotherName1 = explode(" " , $personFounded);
                $expBrotherName2 = explode(" " , $son['wife_name']);
                if($expBrotherName1[0] === $expBrotherName2[1] && end($expBrotherName1) === end($expBrotherName2)){
                    echo '<div class="alert alert-success w-75 mx-auto">'.$son['wife_name'].'</div>';
                }
            }

            // Find Sons That User
            foreach($data as $son){
                // Son Register Before
                $sonBefore = explode(" ",$son['full_name']);
                $memberArr = explode(" ",$personFounded);

                // Parent Of The Before registered Son From Parent
                $ParentName1 = strtolower($memberArr[0].' ' .$memberArr[1]);
                
                // Parent Of The Before registered Son From Son
                $ParentName2 = strtolower($sonBefore[1].' '.$sonBefore[2]);

                // Family Name From Parent 
                $familyName1 = strtolower(end($memberArr));
                // Family Name From Son 
                $familyName2 = strtolower(end($sonBefore));


                if($ParentName1 === $ParentName2 && $familyName1 === $familyName2 && ! in_array($son['full_name'] , explode(PHP_EOL , $personFoundedSons))){
                    
                 
                        echo '<div class="alert alert-danger w-75 mx-auto my-3 shadow">'.$son['full_name'].'</div>';
                 
    
                }
                
             }

            
    





        // Find Brothers That User   
        echo 'Brothers' . '<br>';
        
        foreach($data as $brother){
            $expBrotherName1 = explode(" " , $personFounded);
            $expBrotherName2 = explode(" " , $brother['full_name']);
            if($expBrotherName1[1] === $expBrotherName2[1] && end($expBrotherName1) === end($expBrotherName2) && $personFounded !== $brother['full_name']){
                echo '<div class="alert alert-success w-75 mx-auto">'.$brother['full_name'].'</div>';
            }
        }
        // Find Brothers That Son Of Another user
        foreach($data as $member2){
            // For Escape Output The Same Name As Brother                    
            $brotherArr = explode(PHP_EOL,$member2['sons']);
            $memberArr = explode(" ",$personFounded);
            $fatherName1 = $memberArr[1] . ' ' . $memberArr[2];
            $memFamily = end($memberArr);

            // Loop Through Son's For Get Brother's
            foreach($brotherArr as $brother){
                $expbrother = explode(" " , $brother);
                $fatherName2 = $expbrother[1] . ' ' .$expbrother[2];
                $familyName = end($expbrother);
                
                if($fatherName1 === $fatherName2 && $memFamily === $familyName){
                    if(implode(" " , $expbrother ) !== $personFounded){
                        echo '<div class="alert alert-danger w-75 mx-auto my-3 shadow">'.implode(" " , $expbrother ).'</div>';
                    }
                    
                } 
            }
        }

        // Find Sister That Wife Of Another User
        foreach($data as $brother){
            $expBrotherName1 = explode(" " , $personFounded);
            $expBrotherName2 = explode(" " , $brother['wife_name']);
            if($expBrotherName1[1] === $expBrotherName2[1] && end($expBrotherName1) === end($expBrotherName2)){
                echo '<div class="alert alert-success w-75 mx-auto">'.$brother['wife_name'].'</div>' . '<br><br>';
            }
        }


        // Find Uncle
        $allUserName = explode(" " , $personFounded);
        $brotherFound = 0;
        echo 'Uncle: ';
        foreach($data as $uncle){
            $filter = explode(" " , $uncle['full_name']);
            
            $memFamily = strtolower(end($allUserName));
            $uncleFamily = strtolower(end($filter));

            // If Uncle And Member Has Same Family Name And Member Not Equal To

            $slicemem = array_slice($allUserName,1);

            if( $memFamily == $uncleFamily && $personFounded !== $uncle['full_name']){
                
                $expun= explode(" ",$uncle['full_name']);                                        

                // Filter Father From Being Uncle
                $sliceun2 = array_slice($expun,0);

                if(array_diff($slicemem , $sliceun2) !== Array()){
                    
                    echo '<div class="alert alert-info w-75 mx-auto my-3 shadow">'.implode(" " , $sliceun2).'</div>';
                }
            }
        }
        

        // Find uncle(الخال)
        $expmother = explode(" " , $personFoundedMother);
        $fatherName1 = $expmother[1] . ' ' . $expmother[2];
        $familyName1 = end($expmother);
        echo 'Uncle الخال' . '<br>';
        foreach($data as $uncle2){
            $expfather = explode(" " , $uncle2['full_name']);
            $fatherName2 = $expfather[1] . ' ' . $expfather[2];
            $familyName2 = end($expfather);
            
            if($fatherName1 === $fatherName2 && $familyName1 === $familyName2){
                echo '<div class="alert alert-dark w-75 mx-auto my-3 shadow">'.$uncle2['full_name'].'</div>';
            }
        }
        
                            
                    
    }

}else{
        header('Location: index.php');
    }

?>