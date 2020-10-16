<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0">
<a class="navbar-brand" href="#">
        <img src='https://i.ibb.co/vqfZsQc/family-tree6.jpg' class='rounded-circle mr-2' style=" width: 50px;height: 50px;"/>
      </a>
    <ul class="navbar-nav mr-auto">

      <li class="nav-item">
        <a class="nav-link py-3" href="dashboard.php">
        <?php echo $_SESSION['userName']; ?>
          <img src='https://i.ibb.co/Pz1kMbd/default.png' class='rounded-circle mr-2' style=" width: 30px;height: 30px;"/>
        
        </a>
      </li>
      <li>
      
        
      </li>

    </ul>
</nav>


<div class="w3-sidebar w3-bar-block w3-card w3-animate-left bg-dark position-fixed" style="display: none; width: 100%; top: 0;right:0;z-index:999" id="mySidebar"> 

  <button onclick="closeLeftMenu()" class="w3-bar-item w3-button w3-large bg-danger text-center text-white position-absolute" style="width: 45px; height: 45px; left: 0%; top: 5px"> &times;</button>
  <h2 class='pr-3 text-white mt-2 text-right'>لوحة التحكم</h2>
  <a class="nav-link py-3 text-white w3-bar-item w3-button active mt-3 d-flex align-items-center" href="dashboard.php"> <i class="fas fa-home ml-3"></i>الرئيسية </a>
  <!--<a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="members.php">All Tree's</a>-->
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="members.php?do=Add"><i class="fas fa-user-plus ml-3"></i>اضافة عضو</a>
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="search.php"><i class="fas fa-search ml-3"></i>بحث</a>
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="logout.php"><i class="fas fa-power-off ml-3"></i>تسجيل خروج</a>
</div>

<script>
function openLeftMenu() {
  document.getElementById("mySidebar").style.display = "block";
}

function closeLeftMenu() {
  document.getElementById("mySidebar").style.display = "none";
}
</script>