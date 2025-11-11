<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) {
  header("location:welcome.php");
  exit();
}

// Set default theme cookie
if (!isset($_COOKIE['_theme'])) {
  setcookie("_theme", "light", time() + (86400 * 7), "/");
}

$isLogin = false;
$showError = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  include "./partials/_dbconnect.php";

  // Sanitize inputs
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // Query for user
  $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
  $result = mysqli_query($con, $sql);
  $num = mysqli_num_rows($result);

  if ($num == 1) {
    $rows = mysqli_fetch_assoc($result);

    // Verify password
    if (password_verify($password, $rows['password'])) {
      $isLogin = true;

      // Set session variables
      $_SESSION['isLogin'] = true;
      $_SESSION['id'] = $rows['id'];
      $_SESSION['username'] = $rows['username'];
      $_SESSION['email'] = $rows['email'];
      $_SESSION['phone'] = $rows['phone'];
      $_SESSION['dob'] = $rows['dob']; // store DB 'date' column as 'dob' in session
      $_SESSION['gender'] = $rows['gender'];
      $_SESSION['country'] = $rows['country'];
      $_SESSION['city'] = $rows['city'];

      // Redirect to welcome page
      header("location:welcome.php");
      exit();
    } else {
      $showError = "Invalid password. Please try again.";
    }
  } else {
    $showError = "Invalid email or user not found.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <?php require './partials/_nav.php' ?>
  <?php
  if ($isLogin) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-success alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    <strong>Sucess! You are loged in! </strong>
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
        <h3 class="card-title mb-4 text-center">Login Page</h3>
        <form action="./login.php" method="post" class="needs-validation" novalidate>

          <div class="mb-3">
            <label for="email" class="form-label">Email address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Email should not be empty</div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">Password should not be empty</div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Log in</button>
          </div>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="./signup.php">Sign up here</a>.</p>
      </div>
    </div>
  </div>
  <?php include("./partials/footer.html") ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script src="js/script.js"></script>
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