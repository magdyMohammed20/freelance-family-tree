<?php
    session_start();
    $pageTitle = 'Manage Items';
    // Country Array
    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");


    // If Session Exist Include [init.php] To Allow Navbar And If I Enter To Dashboard Without Login Or Without Start The Session It Will Direct Me To Login Page
    if(isset($_SESSION['userName'])){
        include 'init.php';

        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        if($do == 'Manage'){

            // For Check For Approved Item
            $query = '';
            // Check If I Request Data Of Pending Members
            if(isset($_GET['pending']) && $_GET['pending']== 'pend'){
                $query = 'AND Approved = 0';
            }

            // Inner Join For Merge Table Columns
            $stmt = $conn->prepare("
            SELECT items.*,categories.Name as category_Name,users.user_Name as Publisher
            FROM items
            INNER JOIN categories ON categories.ID = items.Cat_Id
            INNER JOIN users ON users.user_Id = items.Member_Id ");
            $stmt->execute();
            $items = $stmt->fetchAll();

        ?>
<h1 class="text-center mt-3">Manage Items</h1>
<div class="container">
    <div class="table-responsive mt-4">
        <table class="table members-table shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Adding Date</th>
                    <th>Category</th>
                    <th>Publisher</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php
                        foreach($items as $item){
                            echo '<tr>';
                                echo '<td>'. $item['item_Id'] .'</td>';
                                echo '<td>'. $item['Name'] .'</td>';

                                // If Item Description Length Greater Than 47 Characters Shrink It To 47 Character And Print It
                                // Else Put Item Description
                                if(strlen($item['Description']) > 30 ){
                                    echo '<td>'. substr($item['Description'] , 0 , 30)."...".'</td>';
                                }else{
                                    echo '<td>'. $item['Description'] .'</td>';
                                }

                                echo '<td>'. $item['Price'] .'</td>';
                                echo '<td>'. $item['Adding_Date'] .'</td>';
                                echo '<td>'. $item['category_Name'] .'</td>';
                                echo '<td>'. $item['Publisher'] .'</td>';


                            echo "<td class='text-left'>".
                                "<a href='items.php?do=Edit&item_Id=" .$item['item_Id'].
                                "'class='btn btn-primary mr-1'><i class='far fa-edit fa-sm'></i></a>".
                                "<a href='items.php?do=Delete&item_Id=" .$item['item_Id'].
                                "'class='btn btn-danger text-white'>
                                <i class='far fa-trash-alt fa-sm'></i></a>
                                ";
                             // If Item Isn't Approved I Will Display Approved Button Inside Item Table That Redirect Me To Approve Page For Item ApprovingTable
                            if($item['Approve'] == 0){
                                echo '<a class="btn btn-warning text-white" href="items.php?do=Approve&item_Id='.$item["item_Id"].'"'.'>Approve <i class="fas fa-check"></i></a>';
                            }
                            echo    '</td>';
                            echo '</tr>';
                        }
                        ?>

            </tbody>
        </table>
    </div>
    <a href="items.php?do=Add" class="btn btn-primary mb-4">
        Add New Item
        <i class="fa fa-plus ml-1"></i>
    </a>
</div>
<?php }

        else if($do == 'Add'){
            ?>
    <h2 class="text-center my-3">Add New Item</h2>
    <div class="container">
        <form class="edit-form" action="?do=insert" method="POST">
            <div class="form-group my-3">
                <label class="mt-1 p-0">Name</label>
                <input type="text" name="Name" class="form-control" required='required' autocomplete='off'>
            </div>
            <div class="form-group my-3">
                <label class="mt-1 p-0">Description</label>
                <input type="text" name="Description" class="form-control" required='required'>
            </div>
            <div class="form-group my-3">
                <label class="mt-1 p-0">Price</label>
                <input type="text" name="Price" class="form-control" required='required'>
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
                        <label class="mt-1 p-0">Member</label>
                        <select name="member" class="form-control " required='required'>
                            <option value="0">---</option>
                            <?php
                                // Get Members Name
                                $stmt = $conn->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo "<option value=" .$user['user_Id'].">".$user['user_Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
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
    <?php
        }

        else if($do == 'insert'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo '<h1 class="text-center mt-4">Add New Item</h1>';

                $Name = $_POST['Name'];
                $Desc = $_POST['Description'];
                $Price = $_POST['Price'];
                $Country = $_POST['countryMade'];
                $Status = $_POST['Status'];
                $Member = $_POST['member'];
                $Cat = $_POST['category'];
                // For Check Duplication Of User name Before Insert
                $check = checkItems('Name', 'items' , $Name);


                // Validate Inserted User Data
                // Array For Store Errors And Display It
                $formErrors = Array();
                if(empty($Name)){
                    // Append Message To Array
                    $formErrors[] = "Item Name Can't Be Empty";
                }
                if(empty($Desc)){
                    // Append Message To Array
                    $formErrors[] = "Item Description Can't Be Empty";
                }
                if(empty($Price)){
                    // Append Message To Array
                    $formErrors[] = "Item Price Can't Be Empty";
                }
                if(empty($Country)){
                    // Append Message To Array
                    $formErrors[] = "Item Country Made Can't Be Empty";
                }
                if(empty($Status)){
                    // Append Message To Array
                    $formErrors[] = "Item Status Can't Be Empty";
                }
                if(empty($Member)){
                    // Append Message To Array
                    $formErrors[] = "Member Can't Be Empty";
                }
                if(empty($Cat)){
                    // Append Message To Array
                    $formErrors[] = "Item Category Can't Be Empty";
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
                        INSERT INTO items (Name,Description,Price,Adding_Date,Country_Made,Status,Cat_Id,Member_Id)
                        VALUES(? , ? , ? , now() ,? , ?, ?, ?)
                    ");

                    // Excute Query
                    $stmt->execute(Array($Name,$Desc,$Price,$Country,$Status,$Cat,$Member));

                    echo '<div class="alert alert-success w-75 mx-auto mt-3 shadow">Item Added Successfully</div>';
                }

        }else{
            redirectHome('You Can\'t Access This Page Directly',10);
        }
        }
        else if($do == 'Edit'){

            // Check For Cat_Id That Want To Update The Data
            $itemId = isset($_GET['item_Id']) && is_numeric($_GET['item_Id'])?intval($_GET['item_Id']):0;

            // Select Data Depending On UserId
            $stmt = $conn->prepare("
                SELECT *
                FROM items
                WHERE item_Id=?
                "
            );
            // Excute Query
            $stmt->execute(Array($itemId));
            // Fetch Data
            $data = $stmt->fetch(); // Fetch Row Data As Array

            // Check If Row Exist
            if($stmt->rowCount() > 0){

            ?>
            <!-- If Row Exist Show The Form Of Update The Data -->
            <h1 class="text-center my-3">Edit Item</h1>
            <div class="container">
                <form class="edit-form" action="?do=Update" method="POST">
                    <input type="hidden" name='itemId' value="<?php echo $_GET['item_Id']; ?>">
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Item Name</label>
                        <input type="text" name="itemName" class="form-control" autocomplete="off" value="<?php echo $data['Name'];?>">
                    </div>
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Item Description</label>
                        <input type="text" name="itemDesc" class="form-control" autocomplete="new-password" value="<?php echo $data['Description'];?>">
                    </div>
                    <div class="form-group my-3">
                        <label class="mt-1 p-0 text-muted font-weight-bold">Item Price</label>
                        <input type="text" name="itemPrice" class="form-control" value="<?php echo $data['Price'];?>">
                    </div>
                    <div class="form-group my-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="mt-1 p-0 text-muted font-weight-bold">Status</label>
                                <select name="Status" class="form-control " required='required'>
                                    <option value="0" <?php if($data['Status'] == 0) echo 'selected';?>>---</option>
                                    <option value="1" <?php if($data['Status'] == 1) echo 'selected';?>>New</option>
                                    <option value="2" <?php if($data['Status'] == 2) echo 'selected';?>>Like New</option>
                                    <option value="3" <?php if($data['Status'] == 3) echo 'selected';?>>Used</option>
                                    <option value="4" <?php if($data['Status'] == 4) echo 'selected';?>>Very Old</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="mt-1 p-0 text-muted font-weight-bold">Member</label>
                                <select name="publisher" class="form-control " required='required'>
                                    <option value="0">---</option>
                                    <?php
                                        // Get Categories Name
                                        $stmt = $conn->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();
                                        foreach($users as $user){
                                            echo "<option value='" .$user['user_Id']."'";
                                            if($data['Member_Id'] == $user['user_Id']) echo 'selected';
                                            echo ">" . $user['user_Name'] ."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="mt-1 p-0 text-muted font-weight-bold">Category</label>
                                <select name="category" class="form-control " required='required'>
                                    <option value="0">---</option>
                                    <?php
                                        // Get Categories Name
                                        $stmt = $conn->prepare("SELECT * FROM categories");
                                        $stmt->execute();
                                        $cats = $stmt->fetchAll();
                                        foreach($cats as $cat){
                                            echo "<option value='" .$cat['ID']."'";
                                            if($data['Cat_Id'] == $cat['ID']) echo 'selected';
                                            echo ">" . $cat['Name'] ."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
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
                $id = $_POST['itemId'];
                $Name = $_POST['itemName'];
                $Desc = $_POST['itemDesc'];
                $Price = $_POST['itemPrice'];
                $Status = $_POST['Status'];
                $publisher = $_POST['publisher'];
                $category = $_POST['category'];

                // Validate Updated Item Data
                // Array For Store Errors And Display It
                $formErrors = Array();
                if(empty($Name)){
                    // Append Message To Array
                    $formErrors[] = "Item Name Can't Be Empty";
                }
                if(empty($Desc)){
                    // Append Message To Array
                    $formErrors[] = "Item Description Can't Be Empty";
                }
                if(empty($Price)){
                    // Append Message To Array
                    $formErrors[] = "Item Price Can't Be Empty";
                }
                if(empty($Status)){
                    // Append Message To Array
                    $formErrors[] = "Item Status Can't Be Empty";
                }
                if(empty($publisher)){
                    // Append Message To Array
                    $formErrors[] = "Item publisher Status Can't Be Empty";
                }
                if(empty($category)){
                    // Append Message To Array
                    $formErrors[] = "Item Category Can't Be Empty";
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
                        UPDATE items
                        SET Name=?,
                            Description=?,
                            Price=?,
                            Status=?,
                            Cat_Id=?,
                            Member_Id=?

                        WHERE item_Id=?
                        "
                    );
                    // Excute Query
                    $stmt->execute(Array($Name,$Desc,$Price,$Status,$category,$publisher,$id));

                    // Print Number Of Updated Records
                    echo '<div class="alert alert-success w-75 mx-auto text-center">' . $stmt->rowCount() . ' Record Updated' . '</div>';
                }



            }else{
                redirectHome('You Can\'t Access This Page Directly',5);
            }
        }

        else if($do == 'Delete'){
            echo '<h1 class="text-center mt-4">Delete Item</h1>';
            // Check For category id That Want To Update The Data
            $itemId = isset($_GET['item_Id']) && is_numeric($_GET['item_Id'])?intval($_GET['item_Id']):0;

            // Check If Item Id Is Exist In DB Or Not
            $check = checkItems('item_Id','items',$itemId);

            if($check > 0){
                // Select Data Depending On Item Id
                $stmt = $conn->prepare("
                    DELETE
                    FROM items
                    WHERE item_Id=?
                    LIMIT 1"
                );


                // Excute Query
                $stmt->execute(Array($itemId));



                echo '<div class="alert alert-success w-75 mx-auto mt-4">Item Deleted Successfully</div>';
                }else{
                    redirectHome('Item Not Founded' , 10);
                }
            }

            // If I Click On Item Approve Button
            else if($do == 'Approve'){
                echo '<h1 class="text-center mt-4">Approved Item</h1>';
                // Check For User_Id That Want To Update The Data
                $itemId = isset($_GET['item_Id']) && is_numeric($_GET['item_Id'])?intval($_GET['item_Id']):0;

                // Check If User Id Is Exist In DB Or Not
                $check = checkActivated('item_Id','items',$itemId);

                // If Item Id Exist Then Update Items Approve To Be A Approved
                if($check == true){
                    // Select Data Depending On item_Id
                    $stmt = $conn->prepare("
                        UPDATE items
                        SET Approve = 1
                        WHERE item_Id=?
                        LIMIT 1"
                    );
                    // Excute Query
                    $stmt->execute(Array($itemId));

                    echo '<div class="alert alert-success w-75 mx-auto mt-4">Record Updated Successfully And Item Be Approved</div>';
                }
                // If Item Id Not Exist Then Show Error Message
                else{
                    echo '<div class="alert alert-danger w-75 mx-auto mt-4">Failed To Update Item As Approved Item</div>';
                }
            }

        //include $tmp . 'footer.php';

    }else{
        header('Location: index.php');
        exit();
    }
