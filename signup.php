<?php

use Dom\Element;
session_start();
if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) {
  header("location:welcome.php");
}

if (!isset($_COOKIE['_theme'])) {
  setcookie("_theme", "light", time() + (86400 * 7), "/");
}

$username = "";
$email = "";
$showAlert = false;
$showError = false;
$showPassError = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  include "./partials/_dbconnect.php";
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $cpassword = filter_input(INPUT_POST, 'cpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $existsSql = "SELECT `username`, `email` FROM `users` WHERE `username` = '$username' OR `email` = '$email'";
  $existResult = mysqli_query($con, $existsSql);
  $num_of_rows = mysqli_num_rows($existResult);
  if ($num_of_rows > 0) {
    $showError = "Username or email already exists.";
  } else {
    if (strlen($password) >= 6) {
      if (($password == $cpassword)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`, `email`, `password`, `phone`, `date`) VALUES ('$username', '$email', '$hash', NULL, current_timestamp());";
        $result = mysqli_query($con, $sql);
        if ($result) {
          $showAlert = true;
          // $_SESSION['isLogin'] = true;
          // $_SESSION['username'] = $username;
          // $_SESSION['email'] = $email;
          header("location:welcome.php");
        }
      } else {
        $filedError = true;
      }
    } else {
      $showPassError = true;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php require './partials/_nav.php' ?>
  <?php
  if ($showAlert) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-success alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    <strong>Sucess! Your account is now created and you can login </strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }

  if ($showError) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-danger alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $showError . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }
  ?>
  <div class="container d-flex justify-content-center">
    <div class="card shadow-sm my-5 col-12 col-sm-12 col-md-8 col-lg-6">
      <div class="card-body">
        <h3 class="card-title mb-4 text-center">Registration Page</h3>
        <form action="./signup.php" method="post" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input value="<?= $username ?>" type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">Username should not be empty</div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email address:</label>
            <input value="<?= $email ?>" type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Email should not be empty</div>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between">
              <label for="password" class="form-label">Password:</label>
              <i class="fa-solid fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
            </div>
            <input type="password" class="form-control <?= $showPassError ? "is-invalid" : "" ?>" id="password"
              name="password" required>
            <div class="invalid-feedback">Password should more than 6 characters</div>
          </div>

          <div class="mb-3">
            <label for="cpassword" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control <?= $filedError ? "is-invalid" : "" ?>" id="cpassword"
              name="cpassword" required>
            <div class="invalid-feedback">Confirm Password do not match</div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Sign up</button>
          </div>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="./login.php">Login in here</a>.</p>

      </div>
    </div>
  </div>
  <?php include("./partials/footer.html") ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script src="js/script.js"></script>
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', () => {
      // Toggle the type attribute using
      // getAttribure() method
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // Toggle the eye and bi-eye icon
      // this.classList.toggle('bi-eye');
    });
  </script>
  <script>
    const html = document.documentElement;
    const themeRadios = document.querySelectorAll('input[name="themeRadios"]');

    // --- Helper: Get cookie by name ---
    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? match[2] : null;
    }

    // --- Step 1: Apply theme from PHP cookie on load ---
    const savedTheme = getCookie('_theme') || 'dark'; // default fallback
    html.setAttribute('data-bs-theme', savedTheme);

    // Check the correct radio button
    const activeRadio = document.querySelector(`input[name="themeRadios"][value="${savedTheme}"]`);
    if (activeRadio) activeRadio.checked = true;

    // --- Step 2: When user switches theme ---
    themeRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        if (radio.checked) {
          const themeValue = radio.value;

          // Apply immediately
          html.setAttribute('data-bs-theme', themeValue);

          // Update cookie (valid for 7 days)
          document.cookie = `_theme=${themeValue}; path=/; max-age=${60 * 60 * 24 * 7}`;
        }
      });
    });
  </script>

</body>

</html>