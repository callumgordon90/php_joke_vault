<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 

    //var_dump(($_SESSION['username']))
?>

<?php

if (isset($_POST['joke'])) {
    //echo("JOKE SUBMITTED");
    // Retrieve form data
    $joke = $_POST['joke'];




$sql = "INSERT INTO joke (`summary`, `user_id`) VALUES (:joke, :user_id)";
$statement = $pdo->prepare($sql);
//bind parameters
$statement->bindParam(':joke', $joke);
$statement->bindParam(':user_id', $_SESSION['user_id']);

// Execute the statement
$statement->execute();

}
?>

<div class='welcome'>
    <div class='welcome-text'>

        <h1>Think you're funny? Tell us a joke! </h1>

        <br>

        <form action="" method="post" enctype="multipart/form-data">
                <!-- Create Joke -->
                <div class="create_joke">
                <label for="joke">Joke:</label>
                <input type="text" id="joke" name="joke" required>
                <br>
                </div>
                
                <br>
                <!-- Submit Button -->
                <button type="submit" class='red-button' name="create_joke">Create Joke</button>
            </form>
            <br>
    </div>
        <img class='welcome-photo' src='photos\jester.jpg' alt='Site Photo'>
</div>


<?php if(isset($_POST['joke'])){echo("<p> JOKE SUBMITTED</p>");} ?>


<?php include '../joke_base/inc/footer.inc.php'; ?>