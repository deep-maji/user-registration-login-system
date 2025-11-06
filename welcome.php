<?php

session_start();
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
  header("location:login.php");
  exit();
}

if (!isset($_COOKIE['_theme'])) {
  setcookie("_theme", "light", time() + (86400 * 7), "/");
}

$showAlert = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  include "./partials/_dbconnect.php";
  if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $id = $_SESSION['id'];
    $phonesql = "UPDATE `users` SET `phone` = $phone WHERE id = '$id';";
    $result = mysqli_query($con, $phonesql);
    if ($result) {
      $_SESSION['phone'] = $phone;
      header("location:welcome.php");
    }
  } else {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $existsSql = "SELECT `username` FROM `users` WHERE `username` = '$username'";
    $userName_existResult = mysqli_query($con, $existsSql);
    $num_of_rows_username = mysqli_num_rows($userName_existResult);

    $existsSql = "SELECT `email` FROM `users` WHERE `email` = '$email'";
    $email_existResult = mysqli_query($con, $existsSql);
    $num_of_rows_email = mysqli_num_rows($email_existResult);
    if ($num_of_rows_username > 0 && $num_of_rows_email > 0) {
      $showError = "Username or email already exists.";
    } else {
      $id = $_SESSION['id'];
      $sql = "UPDATE `users` SET `username` = '$username', `email` = '$email' WHERE id = '$id';";
      $result = mysqli_query($con, $sql);
      if ($result) {
        $showAlert = true;
        $_SESSION['isLogin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        header("location:welcome.php");
      }
    }
  }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - <?= $_SESSION['username']; ?></title>
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
  <div class="container-fluid my-3">
    <h4>Welcome @<?= $_SESSION['username']; ?></h4>
    <div class="card shadow-sm p-4">
      <div>
        <div class="mb-3" style="cursor: pointer;"><strong>Apperance</strong></div>
        <form>
          <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Theme</legend>
            <div class="col-sm-10">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="themeRadios" id="lightMode" value="light" checked>
                <label class="form-check-label" for="lightMode">Light</label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="themeRadios" id="darkMode" value="dark">
                <label class="form-check-label" for="darkMode">Dark</label>
              </div>
            </div>
          </fieldset>
        </form>
      </div>

      <div>
        <div class="mb-3"><strong>Profile</strong></div>
        <div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
              <input readonly value="<?= $_SESSION['username'] ?>" type="text" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input readonly value="<?= $_SESSION['email'] ?>" type="email" class="form-control">
            </div>
          </div>




          <button class="my-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateProfile"
            aria-expanded="false" aria-controls="collapseExample">
            Edit Profile
          </button>
          <div class="collapse" id="updateProfile">
            <div class="my-3"><strong>Update your profile</strong></div>
            <form action="./welcome.php" method="post">
              <div class="row mb-3">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['username'] ?>" type="text" name="username" class="form-control"
                    id="username">
                </div>
              </div>

              <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['email'] ?>" type="email" name="email" class="form-control" id="email">
                </div>
              </div>

              <button type="submit" class="mb-3 btn btn-primary">Save</button>
            </form>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
              <?php
              if (!empty($_SESSION['phone'])) {
                echo '<div class="d-flex">
                        <input readonly value="' . $_SESSION["phone"] . '" type="number" class="form-control">
                        <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addphone"
                          aria-expanded="false">
                          Update
                        </button>
                      </div>
                      ';
              } else {
                echo '<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addphone"
                        aria-expanded="false">
                        Add
                      </button>';
              }
              ?>

              <div class="collapse mt-3" id="addphone">
                <form action="./welcome.php" method="post">

                  <div class="row mb-3">
                    <div class="col-sm-10 d-flex">
                      <input value="<?= $_SESSION['phone'] ?>" type="number" name="phone" class="form-control"
                        id="phone">
                      <button type="submit" class="mx-3 btn btn-primary">Save</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
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