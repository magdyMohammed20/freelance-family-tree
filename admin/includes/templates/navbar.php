<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link py-3" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link py-3" href="categories.php">Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link py-3" href="items.php">Items</a>
      </li>

      <li class="nav-item dropdown ml-auto">
        <a class="nav-link py-3 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo substr($_SESSION['userName'] , 0,5); ?>
        </a>
        <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
          <a class="dropdown-item text-white" href="logout.php">Logout</a>
        </div>
      </li>

    </ul>
  </div>
</nav>


<script>
    var drop = document.getElementById('navbarDropdown'),
        menu = document.getElementsByClassName('dropdown-menu')[0];
    var open = true;

    drop.onclick = function(){
      if(open){
          menu.style.display = 'block';
          open = false;
      }else{
          menu.style.display = 'none';
          open = true;
      }
    };
</script>
