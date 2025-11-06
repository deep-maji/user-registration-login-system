<?php

if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) {
  $logedIn = true;
} else {
  $logedIn = false;
}

?>

<html data-bs-theme="light">
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="./welcome.php"><i class="fa-solid fa-user-shield"></i></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <!-- <li class="nav-item">
          <a class="nav-link" aria-current="page" href="./welcome.php">Home</a>
        </li> -->
        
        <?php
        if (!$logedIn) {
          echo '<li class="nav-item d-flex pt-2">
          <div class="mx-2">
            <input class="form-check-input" type="radio" name="themeRadios" id="lightMode" value="light"
              checked>
            <label class="form-check-label" for="lightMode">Light</label>
          </div>

          <div class="mx-2">
            <input class="form-check-input" type="radio" name="themeRadios" id="darkMode" value="dark">
            <label class="form-check-label" for="darkMode">Dark</label>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./login.php"><i class="fa-solid fa-right-to-bracket"></i></a>
        </li>
        ';
        } else {
          echo '<li class="nav-item">
          <a class="nav-link" href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        </li>';
        }
        ?>

      </ul>
    </div>
  </div>
</nav>

</html>