<!--
  Author: Adam Minaker
  Date: 3/10/2021
  Description: New game page, allows players to post a new game.
-->
<?php
require 'connect.php';

// Query the DB for location data.
$query = "SELECT LocationID, Name
          FROM locations";
$statement = $db->prepare($query);
$statement->execute();
$locations = $statement->fetchAll();

require 'header.php';
?>
<main>
  <?php if (isset($_SESSION['fname'])) : ?>
    <div class="text-center">
      <h3 class="mt-3 mb-4">New Game</h3>
      <form action="process_game.php" method="post" class="needs-validation" novalidate>
        <div class="mb-1">
          <select class="form-select" id="location" name="location" required>
            <option disabled selected value="">Choose a location...</option>
            <?php foreach ($locations as $location) : ?>
              <option value="<?= $location['LocationID'] ?>"><?= $location['Name'] ?></option>
            <?php endforeach ?>
          </select>
          <div class="invalid-feedback">
            Please choose a location.
          </div>
        </div>
        <div class="mb-4">
          <p>Don't see your local court here? <a id="hyperlink" href="new_location.php?new-game">Add a location.</a></p>
        </div>
        <input class="form-control" type="number" name="duration" placeholder="Game Duration (1-6 hours)" min="1" max="6" required />
        <div class="invalid-feedback">
          Please provide a duration between 1 and 6 hours.
        </div>
        <div class="mb-3">
          <label for="description"></label>
          <textarea class="form-control" style="height: 100px;" name="description" id="description" placeholder="Game Description (Optional)"></textarea>
        </div>
        <input class="btn btn-danger my-2" type="submit" name="command" value="Create Game" />
      </form>
    </div>
  <?php elseif (!isset($_SESSION['fname'])) : ?>
    <section class="py-5 text-center container">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">Please log in to create a new game.</h1>
          <p>
            <a href="login.php" class="btn btn-danger my-2">Login</a>
            <a href="register.php" class="btn btn-danger my-2">Register</a>
          </p>
        </div>
      </div>
    </section>
  <?php endif ?>
</main>
<?php require 'footer.php'; ?>