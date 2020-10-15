<?php

    session_start();
    $pageTitle = 'Create New Ad';
    include 'init.php';
    // Country Array
    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");


// Check If User Login I Will Show All Data And Profile Else Will Direct Me To Login Page
    if($sessionUser){

        // Get Datq To Insert It To DB
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $formErrors = array();

            $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['Description'],FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['Price'],FILTER_SANITIZE_NUMBER_INT);
            $country = filter_var($_POST['countryMade'],FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['Status'],FILTER_SANITIZE_NUMBER_INT);
            $cat = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
            $MemberId = $_SESSION['userId']; // Get User Id For Insert To DB
            $itemImage = $_FILES['pic']; // Get User Image



            // Item Image
            $itemName = $_FILES['pic']['name'];
            $itemSize = $_FILES['pic']['size'];
            $itemTmpName = $_FILES['pic']['tmp_name'];
            $itemType = $_FILES['pic']['type'];

            // List Of Allowed Image Extensions
            $itemExtensions = array('jpeg','jpg','gif','png');

            // Get Item Image Extension
            // Convert Name Of Image To Array For Get The Extension
            $itemExtension = explode('.',$itemName);

            // Get The Image Extension And Convert It To Small Characters
            $itemExten = strtolower(end($itemExtension));


            if(strlen($name) < 3){
                $formErrors[] = 'Item Name Should Be Greater Than 3 Characters';
            }

            if(strlen($desc) < 10){
                $formErrors[] = 'Item Description Should Be Greater Than 10 Characters';
            }

            if(strlen($country) < 2){
                $formErrors[] = 'Item Country Made Should Be Greater Than 2 Characters';
            }

            if($price < 1){
                $formErrors[] = 'Item Price Should Be Greater Than or Equal 1$';
            }

            // If User Upload Image With Unpopular Extension
            if(!empty($itemName) && !in_array($itemExten , $itemExtensions)){
                $formErrors[] = "This Type Of Image Not Allowed Please Upload Image With <strong>png , jpg , jpeg , gif</strong> Extension";
            }

            // If User Not Upload Image
            if(empty($itemName)){
                $formErrors[] = "You Must Upload Item Image";
            }

            // If User Upload Image With Size Greater Than 4 MB
            if($itemSize > 4194304){
              $formErrors[] = "You Must Upload Image With Size Less Than 4MB";

            }


           // If There Is No Form Errors I Will Insert Item Data To DB
           if(count($formErrors) == 0){

               // Prevent Duplication Name Of Uploaded Images
                $iImage = rand(0,100000000000000) .'_'. $itemName;
                // Move The Uploaded Image To My Folders
                move_uploaded_file($itemTmpName,"layout\images\\".$iImage);

                // Update Data Depending On UserId

               $stmt = $conn->prepare("
                    INSERT INTO items (Name,Description,Price,Adding_Date,Country_Made,Image,Status,Cat_Id,Member_Id)
                    VALUES(? , ? , ? , now() ,? , ? , ?, ? , ?)
                ");

                // Excute Query
                $stmt->execute(Array($name,$desc,$price . '$',$country,"layout/images/".$iImage,$status,$cat,$MemberId));

                echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow">Item Added Successfully</div>';

           }


        }
?>
<div class="container mt-4">
       <h1>Create New Item</h1>
    <div class="row">
        <div class="information col-12 mt-3">
            <div class="card">
                <div class="card-header text-white">
                    <i class="fab fa-buysellads fa-lg mr-2"></i>My Items
                </div>
                <div class="card-body p-3">
                    <div class="row">
                      <div class="formErrors col-12">
                        <?php
                            if(!empty($formErrors)){
                                foreach($formErrors as $err){
                                    echo '<div class="alert alert-danger w-100 mx-auto">'.$err.'</div>';
                                }
                            }
                        ?>
                      </div>
                       <div class="col-8">
                          <!-- Start Add New Item By User & [multipart/form-data] Is Encoding Type For File Uploading-->
                           <form class="edit-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label class="mt-1 p-0">Name</label>
                                    <input type="text" name="name" class="live-name form-control" required='required' autocomplete='off'>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mt-1 p-0">Add Item Image</label>
                                    <input type="file" name="pic" class='live-img' required='required'>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mt-1 p-0">Description</label>
                                    <input type="text" name="Description" class="live-desc form-control" required='required'>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mt-1 p-0">Price</label>
                                    <input type="text" name="Price" class="live-price form-control" required='required'>
                                </div>
                                <div class="form-group my-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="mt-1 p-0">Country Made</label>
                                            <select name="countryMade" class="form-control" required='required'>
                                                <?php
                                                    foreach($countries as $country){
                                                        echo '<option>'.$country.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="mt-1 p-0">Status</label>
                                            <select name="Status" class="form-control " required='required'>
                                                <option value="0">---</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group my-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="mt-1 p-0">Category</label>
                                            <select name="category" class="form-control " required='required'>
                                                <option value="0">---</option>
                                                <?php
                                                    // Get Categories Name
                                                    $stmt = $conn->prepare("SELECT * FROM categories");
                                                    $stmt->execute();
                                                    $cats = $stmt->fetchAll();
                                                    foreach($cats as $cat){
                                                        echo "<option value=" .$cat['ID'].">".$cat['Name']."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-3">Add Item <i class="fa fa-plus ml-1"></i></button>
                            </form>
                       </div>
                       <!-- For See Ad Editing Live -->
                        <div class="col-4 p-4 ">
                            <div class="card position-relative live-preview">
                              <span class="position-absolute price card-price-preview text-white p-1 card-desc-price">0$</span>
                              <img src="layout/images/3.png" class="card-img-top img-preview p-4" alt="...">
                              <div class="card-body">

                                <h5 class="card-title card-name-preview font-weight-bold mt-2">Name</h5>
                                <p class="card-text text-muted card-desc-preview">Description</p>
                                <p class="text-right">
                                    <span class="text-muted card-date-preview"></span>
                                    <i class="far fa-calendar-alt mr-2"></i>
                                </p>

                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }else{
        // If Login Without Enter Login Data Will Direct Me To Login Page
        header('Location: login.php');
    }include $tmp . 'footer.php';?>

<style>
    .loginASign{
        display: none !important;
    }
    .logo{
        margin-right: 5px !important
    }
    .card-header{
        background-color: #343a40;
    }
    .card-header i{
        color: #ee5253
    }
    .user-img{
        top: 17px;
        right: 15px;
        border: 3px solid #FFF;
    }
</style>

<script>
    // Get The Current Date For Live Preview
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear();
    $fullDate =  day + "-" + month + "-" + year;
    $('.card-date-preview').text($fullDate);

    // Live Preview Name
    $(".live-name").keyup(function(){
        $('.card-name-preview').text($(this).val());
    });

    // Live Preview Description
    $(".live-desc").keyup(function(){
        $('.card-desc-preview').text($(this).val());
    });

    // Live Preview Price
    $(".live-price").keyup(function(){
        $('.card-price-preview').text($(this).val());
    });

</script>
