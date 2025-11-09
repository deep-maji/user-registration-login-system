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
$phoneLenError = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  include "./partials/_dbconnect.php";
  $id = $_SESSION['id'];

  // 1️⃣ Update phone
  if (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    if (strlen($phone) == 10) {
      $phonesql = "UPDATE `users` SET `phone` = '$phone' WHERE `id` = '$id'";
      $result = mysqli_query($con, $phonesql);
      if ($result) {
        $_SESSION['phone'] = $phone;
        $showAlert = "Phone updated successfully!";
      }
    } else {
      $phoneLenError = "Phone number must be 10 digits long.";
    }
  }

  // 2️⃣ Update date
  elseif (isset($_POST['dob'])) {
    $dob = $_POST['dob'];
    $sql = "UPDATE `users` SET `dob` = '$dob' WHERE `id` = '$id'";
    if (mysqli_query($con, $sql)) {
      $_SESSION['dob'] = $dob;
      $showAlert = "Date of birth updated successfully!";
    }
  }

  // 3️⃣ Update gender
  elseif (isset($_POST['gender'])) {
    $gender = $_POST['gender'];
    $sql = "UPDATE `users` SET `gender` = '$gender' WHERE `id` = '$id'";
    if (mysqli_query($con, $sql)) {
      $_SESSION['gender'] = $gender;
      $showAlert = "Gender updated successfully!";
    }
  }

  // 4️⃣ Update country
  elseif (isset($_POST['country'])) {
    $country = trim($_POST['country']);
    $sql = "UPDATE `users` SET `country` = '$country' WHERE `id` = '$id'";
    if (mysqli_query($con, $sql)) {
      $_SESSION['country'] = $country;
      $showAlert = "Country updated successfully!";
    }
  }

  // 5️⃣ Update city
  elseif (isset($_POST['city'])) {
    $city = trim($_POST['city']);
    $sql = "UPDATE `users` SET `city` = '$city' WHERE `id` = '$id'";
    if (mysqli_query($con, $sql)) {
      $_SESSION['city'] = $city;
      $showAlert = "City updated successfully!";
    }
  }

  // 6️⃣ Update username & email (same as your original logic)
  elseif (isset($_POST['username']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $existsUsername = mysqli_query($con, "SELECT `id` FROM `users` WHERE `username`='$username' AND id!='$id'");
    $existsEmail = mysqli_query($con, "SELECT `id` FROM `users` WHERE `email`='$email' AND id!='$id'");

    if (mysqli_num_rows($existsUsername) > 0 || mysqli_num_rows($existsEmail) > 0) {
      $showError = "Username or email already exists.";
    } else {
      $sql = "UPDATE `users` SET `username`='$username', `email`='$email' WHERE id='$id'";
      if (mysqli_query($con, $sql)) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $showAlert = "Profile updated successfully!";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    #phone::-webkit-outer-spin-button,
    #phone::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<body>
  <?php require './partials/_nav.php' ?>
  <?php
  if ($showAlert) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-success alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $showAlert . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }

  if ($showError) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-danger alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $showError . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }

  if ($phoneLenError) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-danger alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $phoneLenError . '
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
            <form action="./welcome.php" method="post" class="needs-validation" novalidate>
              <div class="row mb-3">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['username'] ?>" type="text" name="username" class="form-control"
                    id="username" required>
                  <div class="invalid-feedback">username should not be empty</div>
                </div>
              </div>

              <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['email'] ?>" type="email" name="email" class="form-control" id="email"
                    required>
                  <div class="invalid-feedback">Email should not be empty</div>
                </div>
              </div>

              <button type="submit" class="mb-3 btn btn-primary">Save</button>
            </form>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
              <?php
              // echo $_SESSION['phone'];
              if ($_SESSION['phone'] != 'NULL') {
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
                <form action="./welcome.php" method="post" class="needs-validation" novalidate>

                  <div class="row mb-3">
                    <div class="col-sm-10 d-flex">
                      <input value="<?= $_SESSION['phone'] ?>" type="number" name="phone" class="form-control"
                        id="phone" required>
                      <!-- <div class="invalid-feedback">Phone number should 10 digits</div> -->
                      <button type="submit" class="mx-3 btn btn-primary">Save</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Date of Birth</label>
            <div class="col-sm-10">
              <div class="d-flex">
                <input readonly value="<?= $_SESSION['dob'] ?? '' ?>" type="date" class="form-control">
                <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateDate"
                  aria-expanded="false">
                  Update
                </button>
              </div>
              <div class="collapse mt-3" id="updateDate">
                <form action="./welcome.php" method="post">
                  <div class="d-flex">
                    <input type="date" name="dob" value="<?= $_SESSION['dob'] ?? '' ?>" class="form-control" required>
                    <button type="submit" class="mx-3 btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-10">
              <div class="d-flex">
                <input readonly value="<?= $_SESSION['gender'] ?? '' ?>" type="text" class="form-control text-capitalize">
                <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateGender"
                  aria-expanded="false">
                  Update
                </button>
              </div>
              <div class="collapse mt-3" id="updateGender">
                <form action="./welcome.php" method="post">
                  <select class="form-select" name="gender" required>
                    <option value="">Select gender</option>
                    <option value="male" <?= ($_SESSION['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ($_SESSION['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
                    <option value="others" <?= ($_SESSION['gender'] ?? '') == 'others' ? 'selected' : '' ?>>Others</option>
                  </select>
                  <button type="submit" class="mt-2 btn btn-primary">Save</button>
                </form>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Country</label>
            <div class="col-sm-10">
              <div class="d-flex">
                <input readonly value="<?= $_SESSION['country'] ?? '' ?>" type="text" class="form-control text-capitalize">
                <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateCountry"
                  aria-expanded="false">
                  Update
                </button>
              </div>
              <div class="collapse mt-3" id="updateCountry">
                <form action="./welcome.php" method="post">
                  <div class="d-flex">
                    <input type="text" name="country" value="<?= $_SESSION['country'] ?? '' ?>" class="form-control text-capitalize" required>
                    <button type="submit" class="mx-3 btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">City</label>
            <div class="col-sm-10">
              <div class="d-flex">
                <input readonly value="<?= $_SESSION['city'] ?? '' ?>" type="text" class="form-control text-capitalize">
                <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateCity"
                  aria-expanded="false">
                  Update
                </button>
              </div>
              <div class="collapse mt-3" id="updateCity">
                <form action="./welcome.php" method="post">
                  <div class="d-flex">
                    <input type="text" name="city" value="<?= $_SESSION['city'] ?? '' ?>" class="form-control" required>
                    <button type="submit" class="mx-3 btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="logoutModalLabel">
          <i class="fa-solid fa-right-from-bracket me-2 text-danger"></i> Confirm Logout
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p class="mb-3 fs-6 text-secondary">
          Are you sure you want to log out from your account?
        </p>
      </div>

      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <form action="logout.php" method="post">
          <button type="submit" class="btn btn-danger px-4">
            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

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