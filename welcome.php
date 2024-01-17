<?php
session_start();
require '../joke_base/inc/auth.inc.php';
require '../joke_base/inc/header.inc.php';

// Function to toggle follow status
function toggleFollowStatus($pdo, $followerId, $followingId)
{
    // Check if the logged-in user is already following the profile user
    $isFollowing = isFollowing($pdo, $followerId, $followingId);

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        if ($isFollowing) {
            // Unfollow - Subtract 1 from 'following' of the logged-in user and 'followers' of the profile user
            $sql = "UPDATE user SET following = following - 1 WHERE id = :follower_id;
                    UPDATE user SET followers = followers - 1 WHERE id = :following_id;";
        } else {
            // Follow - Add 1 to 'following' of the logged-in user and 'followers' of the profile user
            $sql = "UPDATE user SET following = following + 1 WHERE id = :follower_id;
                    UPDATE user SET followers = followers + 1 WHERE id = :following_id;";
        }

        // Execute the multi-query
        $pdo->exec($sql);

        // Commit the transaction
        $pdo->commit();

        // Return true to indicate success
        return true;
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        
        // Return false in case of an error
        return false;
    }
}


// Function to check if the logged-in user is already following the profile user
function isFollowing($pdo, $followerId, $followingId)
{
    $sql = "SELECT COUNT(*) FROM user WHERE id = :follower_id AND FIND_IN_SET(:following_id, following) > 0";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':follower_id', $followerId);
    $statement->bindParam(':following_id', $followingId);
    $statement->execute();

    return $statement->fetchColumn() > 0;
}


$followStatus = false; // Initialize $followStatus

if (isset($_GET['user_id'])) {

    //var_dump($_GET);

    $sql = "SELECT user.id, user.name, user.username, user.password, user.photo, user.following, user.followers, COUNT(joke.id) AS joke_count
            FROM user
            LEFT JOIN joke ON user.id = joke.user_id
            WHERE user.id = :user_id
            GROUP BY user.id";

    //Prepare the sql statement
    $statement = $pdo->prepare($sql);

    $statement->bindParam(':user_id', $_GET['user_id']);
    $statement->execute();

    //Fetch the user 
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the "Follow" button is clicked
    if (isset($_POST['vote']) && $_POST['vote'] === 'up') {
        // Toggle follow status
        if ($_SESSION['user_id'] !== $_GET['user_id']) {
            // Update 'following' column of the logged-in user
            // Update 'followers' column of the profile user
            $followStatus = toggleFollowStatus($pdo, $_SESSION['user_id'], $_GET['user_id']);
        }
    }

    //var_dump($user);
    //display user profile:
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

    // Display follow/unfollow button
    if ($_SESSION['user_id'] !== $_GET['user_id']) {
        echo "<form action='' method='post'>";
        echo "<button type='submit' class='red-button-2' name='vote' value='up'>" . ($followStatus ? 'Unfollow' : 'Follow') . "</button>";
        echo "</form>";
    }

    echo "</div>";
}

?>

<?php include '../joke_base/inc/footer.inc.php'; ?>
