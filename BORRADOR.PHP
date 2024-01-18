<?php
session_start();
require '../joke_base/inc/auth.inc.php';
require '../joke_base/inc/header.inc.php'; 
?>

<?php
if (isset($_SESSION['user_id'])) {
    $sql = "SELECT user.id, user.name, user.username, user.password, user.photo, user.following, user.followers, COUNT(joke.id) AS joke_count
            FROM user
            LEFT JOIN joke ON user.id = joke.user_id
            WHERE user.id = :user_id
            GROUP BY user.id";

    // Prepare the SQL statement
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $_SESSION['user_id']);
    $statement->execute();

    // Fetch the user 
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // EDIT THE USER DETAILS UPDATE LOGIC:
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate and update user information here

        // For example, update the name and username
        $newName = !empty($_POST['new_name']) ? $_POST['new_name'] : $user['name'];
        $newUsername = !empty($_POST['new_username']) ? $_POST['new_username'] : $user['username'];

        $updateSql = "UPDATE `user` SET `name` = :new_name, `username` = :new_username
                      WHERE `id` = :user_id";

        $updateStatement = $pdo->prepare($updateSql);
        $updateStatement->bindParam(':new_name', $newName);
        $updateStatement->bindParam(':new_username', $newUsername);
        $updateStatement->bindParam(':user_id', $_SESSION['user_id']);
        $updateStatement->execute();

        // I can similarly update other fields and perform validation as needed
        // Handle file upload for the new photo
        if (!empty($_FILES['new_photo']['name'])) {
            // Implement logic to handle file upload and update the database
            // Move the uploaded file to the appropriate directory
            $targetDirectory = '../joke_base/photos/';
            $targetFile = $targetDirectory . basename($_FILES['new_photo']['name']);
            move_uploaded_file($_FILES['new_photo']['tmp_name'], $targetFile);

            // Update the database with the new photo filename
            $updatePhotoSql = "UPDATE `user` SET `photo` = :new_photo WHERE `id` = :user_id";
            $updatePhotoStatement = $pdo->prepare($updatePhotoSql);
            $updatePhotoStatement->bindParam(':new_photo', $_FILES['new_photo']['name']);
            $updatePhotoStatement->bindParam(':user_id', $_SESSION['user_id']);
            $updatePhotoStatement->execute();
        }

        // Update session variables with new data (for fresh display after hitting the edit button)
        $_SESSION['name'] = $newName;
        $_SESSION['username'] = $newUsername;
        // Add other variables as needed
        
        // Set the notification message
        $_SESSION['notification'] = '<h1>Your profile has been successfully updated! <h1>';
    }

    // Display notification if available
    if (isset($_SESSION['notification'])) {
        echo '<div class="notification">' . $_SESSION['notification'] . '</div>';
        unset($_SESSION['notification']); // Clear the notification after displaying it
    }

    // Display user profile
    echo "<div class='profile-container'>";
    echo "<div class='profile-names'>";
    echo "<h1 class='profile-name'>Name: {$user['name']}</h1>";
    echo "<h1 class='profile-alias'>Alias: {$user['username']} </h1>";
    echo "</div>";

    echo "<img class='profile-photo' src='../joke_base/photos/{$user['photo']}' alt='User Photo'>";

    echo "<div class='profile-stats'>";
    echo "<h2>Jokes Created: {$user['joke_count']}</h2><br>";
    echo "<h2>Following: {$user['following']}</h2>";
    echo "<h2>Followed by: {$user['followers']}</h2>";
    echo "</div>";

    // Edit profile form
    echo "<h2>Edit Profile:</h2>";
    echo "<form action='' method='post' enctype='multipart/form-data'>";
    echo "New Name: <input type='text' name='new_name' value='{$user['name']}'><br>";
    echo "New Alias: <input type='text' name='new_username' value='{$user['username']}'><br>";
    echo "New Photo: <input type='file' name='new_photo'><br>";
    echo "<button type='submit' class='red-button'>Save Changes</button>";
    echo "</form>";

    echo "</div>";
}

?>

<?php include '../joke_base/inc/footer.inc.php'; ?>