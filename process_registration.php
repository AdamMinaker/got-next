<!--
  Author: Adam Minaker
  Date: 3/18/2021
  Description: Processes the registration script.
-->
<?php
require 'connect.php';

$valid_registration = true;

// Sanitize inputs.
$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$confirm_password = filter_input(INPUT_POST, 'cpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Query the DB for accounts with the submitted email address.
$query = "SELECT Email
          FROM players
          WHERE Email = :Email";
$statement = $db->prepare($query);
$statement->bindvalue(':Email', $email);
$statement->execute();
$player = $statement->fetch();

// Check if a player with submitted email address already exists.
if (!empty($player)) {
  $valid_registration = false;
// Check if inputs are valid.
} elseif (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($confirm_password)) {
  $valid_registration = false;
// Check if passwords match.
} elseif ($password != $confirm_password) {
  $valid_registration = false;
} else {
  //Hash and salt password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new player into the DB.
  $query = "INSERT INTO players (fname, lname, email, password) 
            VALUES (:fname, :lname, :email, :password)";
  $statement = $db->prepare($query);
  $statement->bindvalue(':fname', $fname);
  $statement->bindvalue(':lname', $lname);
  $statement->bindvalue(':email', $email);
  $statement->bindvalue(':password', $hashed_password);
  $statement->execute();
}

require 'header.php';
?>
<main>
  <section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <?php if ($valid_registration): ?>
          <h1 class="fw-light">Registered Successfully!</h1>
          <p class="lead text-muted">You can now login to join and create games.</p>
          <p>
            <a href="login.php" class="btn btn-danger my-2">Login</a>
          </p>
        <?php else: ?>
          <h1 class="fw-light">There was an issue.</h1>
          <p class="lead text-muted">Please make sure your passwords match and try again.</p>
          <p>
            <a href="register.php" class="btn btn-danger my-2">Try Again</a>
          </p>
        <?php endif ?>
      </div>
    </div>
  </section>
</main>
<?php require 'footer.php'; ?>