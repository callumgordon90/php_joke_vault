<?php
session_start();
require '../joke_base/inc/auth.inc.php';
require '../joke_base/inc/header.inc.php';


if (isset($_GET['user_id'])) {

    //CHECK THAT I HAVE THE ID's OF THE LOGGED IN USER AND THE STRANGER USER:
    //var_dump($_GET);
    //var_dump($_SESSION);

    //////////////////////////////////////////////////////////////////////////////
    // Check if the "Follow" button is clicked
    if (isset($_POST['vote'])) {
        // Ensure the logged-in user is not following themselves
        if ($_SESSION['user_id'] !== $_GET['user_id']) {
            // Prepare the SQL statements to update 'following' and 'followers'
            $sqlFollowing = "UPDATE user SET following = following + 1 WHERE id = :follower_id";
            $sqlFollowers = "UPDATE user SET followers = followers + 1 WHERE id = :following_id";

            try {
                // Begin a transaction
                $pdo->beginTransaction();

                // Execute the SQL statements
                $pdo->prepare($sqlFollowing)->execute([':follower_id' => $_SESSION['user_id']]);
                $pdo->prepare($sqlFollowers)->execute([':following_id' => $_GET['user_id']]);

                // Commit the transaction
                $pdo->commit();

                // Redirect to the same page to avoid form resubmission on page refresh
                header("Location: stranger_profile.php?user_id={$_GET['user_id']}");
                exit();
            } catch (PDOException $e) {
                // Rollback the transaction in case of an error
                $pdo->rollBack();
                
                // Handle the error (you can log or display an error message)
                echo "Error: " . $e->getMessage();
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////////////
    //CHECK IF THE 'UNFOLLOW BUTTON IS CLICKED:
    if (isset($_POST['downvote'])) {
        // Ensure the logged-in user is not following themselves
        if ($_SESSION['user_id'] !== $_GET['user_id']) {
            // Prepare the SQL statements to update 'following' and 'followers'
            $sqlFollowing = "UPDATE user SET following = following - 1 WHERE id = :follower_id";
            $sqlFollowers = "UPDATE user SET followers = followers - 1 WHERE id = :following_id";

            try {
                // Begin a transaction
                $pdo->beginTransaction();

                // Execute the SQL statements
                $pdo->prepare($sqlFollowing)->execute([':follower_id' => $_SESSION['user_id']]);
                $pdo->prepare($sqlFollowers)->execute([':following_id' => $_GET['user_id']]);

                // Commit the transaction
                $pdo->commit();

                // Redirect to the same page to avoid form resubmission on page refresh
                header("Location: stranger_profile.php?user_id={$_GET['user_id']}");
                exit();
            } catch (PDOException $e) {
                // Rollback the transaction in case of an error
                $pdo->rollBack();
                
                // Handle the error (you can log or display an error message)
                echo "Error: " . $e->getMessage();
            }
        }
    }


    ///////////////////////////////////////////////////////////////////////////////


    //SELECT THE STRANGER USER:
    $sql = "SELECT user.id, user.name, user.username, user.password, user.photo, user.following, user.followers, COUNT(joke.id) AS joke_count
            FROM user
            LEFT JOIN joke ON user.id = joke.user_id
            WHERE user.id = :user_id";
           

    //Prepare the sql statement
    $statement = $pdo->prepare($sql);

    $statement->bindParam(':user_id', $_GET['user_id']);
    $statement->execute();

    //Fetch the stranger user 
    $strangerUser = $statement->fetch(PDO::FETCH_ASSOC);

    

    var_dump($strangerUser);


    //display user profile:
    echo "<div class='profile-container'>";
    echo "<div class='profile-names'>";
    echo "<h1 class='profile-name'>Name: {$strangerUser['name']}</h1>";
    echo "<h1 class='profile-alias'>Alias: {$strangerUser['username']} </h1>";
    echo "</div>";

    echo "<img class='profile-photo' src='../joke_base/photos/{$strangerUser['photo']}' alt='User Photo'>";

    echo "<div class='profile-stats'>";
    echo "<h2>Jokes Created: {$strangerUser['joke_count']}</h2><br>";
    echo "<h2>Following: {$strangerUser['following']}</h2>";
    echo "<h2>Followed by: {$strangerUser['followers']}</h2>";
    echo "</div>";

    // Display follow/unfollow button
    if ($_SESSION['user_id'] !== $_GET['user_id']) {
        //FOLLOW BUTTON:
        echo "<form action='' method='post'>";
        echo "<button type='submit' class='red-button-2' name='vote'> Follow </button>";
        echo "</form>";

        //UNFOLLOW BUTTON:
        echo "<form action='' method='post'>";
        echo "<button type='submit' class='red-button-2' name='downvote'> Unfollow </button>";
        echo "</form>";
    }

    echo "</div>";
}

?>

<?php include '../joke_base/inc/footer.inc.php'; ?>