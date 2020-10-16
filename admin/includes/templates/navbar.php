<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0">

    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link py-3" href="dashboard.php">
          <img src='../../layout/images/default.png'/>
        <?php echo substr($_SESSION['userName'] , 0,5); ?>
        </a>
      </li>

    </ul>
</nav>


<div class="w3-sidebar w3-bar-block w3-card w3-animate-left bg-dark position-fixed" style="display: none; width: 20%; top: 0;left:0" id="leftMenu"> 

  <button onclick="closeLeftMenu()" class="w3-bar-item w3-button w3-large bg-danger text-center text-white position-absolute" style="width: 45px; height: 45px; left: 100%; top: 5px"> &times;</button>
  <h2 class='pl-3 text-white mt-2'>Dashboard</h2>
  <a class="nav-link py-3 text-white w3-bar-item w3-button active mt-3 d-flex align-items-center" href="dashboard.php"> <i class="fas fa-home mr-3"></i>Home </a>
  <!--<a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="members.php">All Tree's</a>-->
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="members.php?do=Add"><i class="fas fa-user-plus mr-3"></i>Add New Member</a>
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="search.php"><i class="fas fa-search mr-3"></i>Search</a>
  <a class="nav-link py-3 text-white w3-bar-item w3-button d-flex align-items-center" href="logout.php"><i class="fas fa-power-off mr-3"></i>Logout</a>
</div>

<script>
function openLeftMenu() {
  document.getElementById("leftMenu").style.display = "block";
  document.getElementsByClassName('w3-open')[0].style.display = "none"
}

function closeLeftMenu() {
  document.getElementById("leftMenu").style.display = "none";
  document.getElementsByClassName('w3-open')[0].style.display = "block"
}
</script>