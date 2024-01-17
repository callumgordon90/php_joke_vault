<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>

<?php
    if(isset($_SESSION['user_id'])){

        $sql = "SELECT user.id, user.name, user.username, user.password, user.photo, user.following, user.followers, COUNT(joke.id) AS joke_count
            FROM user
            LEFT JOIN joke ON user.id = joke.user_id
            WHERE user.id = :user_id
            GROUP BY user.id";

        //Prepare the sql statement
        $statement = $pdo->prepare($sql);

        $statement->bindParam(':user_id', $_SESSION['user_id']);
        $statement->execute();

        //Fetch the user 
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        //var_dump($user);

        //EDIT THE USER DETAILS UPDATE LOGIC:
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and update user information here

            // For example, update the name and username
            $newName = $_POST['new_name'];
            $newUsername = $_POST['new_username'];
            

            $updateSql = "UPDATE `user` SET `name` = :new_name, `username` = :new_username, 
                          WHERE `id` = :user_id";

            $updateStatement = $pdo->prepare($updateSql);
            $updateStatement->bindParam(':new_name', $newName);
            $updateStatement->bindParam(':new_username', $newUsername);

            /*
            // Hash the new password (for security)
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStatement->bindParam(':new_password', $hashedPassword);
            */

            $updateStatement->bindParam(':user_id', $_SESSION['user_id']);
            $updateStatement->execute();

            // I can similarly update other fields and perform validation as needed
            // Handle file upload for the new photo
            if (!empty($_FILES['new_photo']['name'])) {
            // Implement logic to handle file upload and update the database
            // Move the uploaded file to the appropriate directory
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

        ?>

        

        <?php
            echo(
                "<div class='profile-container'>" .

                    "<div class='profile-names'>" .
                        "<h1 class='profile-name'>Name: {$user['name']}</h1>" .
                        "<h1 class='profile-alias'>Alias: {$user['username']} </h1>" .
                    "</div> " . 

                        "<img class='profile-photo' src='../joke_base/photos/{$user['photo']}' alt='User Photo'>" .

                        "<div class='profile-stats'>" .
                        "<h2>Jokes Created: {$user['joke_count']}</h2>" . "<br>" .
                        "<h2>Following: {$user['following']}</h2>" .
                        "<h2>Followed by: {$user['followers']}</h2> " .
                        "</div> "
                . "</div> "
            );
        ?>

        

            <?php
            // Edit profile form
            echo "<h2>Edit Profile:</h2>";
            echo "<form action='' method='post'>";
            echo "New Name: <input type='text' name='new_name' value='{$user['name']}'><br>";
            echo "New Alias: <input type='text' name='new_username' value='{$user['username']}'><br>";
            echo "New Photo: <input type='file' name='new_photo'><br>";
            echo "<button type='submit' class='red-button'>Save Changes</button>";
            echo "</form>";
        


    }
?>


<?php include '../joke_base/inc/footer.inc.php'; ?>