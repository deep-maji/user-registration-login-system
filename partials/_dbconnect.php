<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "users";

try {
  $con = mysqli_connect($hostname, $username, $password, $database);
  if (!$con) {
    die("Error : " . mysqli_connect_error());
  }
} catch (\Throwable $th) {
  echo $th->getMessage();
  die("Error : " . mysqli_connect_error());
}

?>