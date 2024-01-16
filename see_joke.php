<?php
session_start();
require '../joke_base/inc/auth.inc.php';
require '../joke_base/inc/header.inc.php';

// First of all, Get ALL the jokes:
$sql = "SELECT joke.id, joke.summary, joke.vote_up, user.name, user.username
        FROM joke
        JOIN user ON joke.user_id = user.id";

$statement = $pdo->query($sql);
$jokeset = $statement->fetchAll(PDO::FETCH_ASSOC);

// Now define functions:

// Function to get random joke from the array:
function getRandomJoke($jokeset)
{
    $randomIndex = array_rand($jokeset);
    return $jokeset[$randomIndex];
}

// Function to vote a joke:
function vote($pdo, $jokeId, $action)
{
    $sql = "UPDATE joke SET vote_up = vote_up " . ($action === 'up' ? '+' : '-') . " 1 WHERE id = :jokeId";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':jokeId', $jokeId);
    $statement->execute();
}


// Button to get a random joke
echo("<h2> Spin the wheel! Hit the button to see a random joke. </h2>
<form action='' method='post'>
    <button type='submit' class='red-button' name='getRandomJoke'> COME ON, MAKE ME LAUGH </button>
</form>");

// Print a random joke when a user hits the button:
if (isset($_POST['getRandomJoke'])) {
    $randomJoke = getRandomJoke($jokeset);

    echo("<h1 class='joke-text'> {$randomJoke['summary']}</h1><p> by {$randomJoke['name']} AKA {$randomJoke['username']} </p>");

    echo(
        "<h2>Did that make you laugh? Rate the joke: </h2>  
            <div class='vote-buttons'>
                <form action='' method='post'>
                    <input type='hidden' name='jokeId' value='{$randomJoke['id']}'>
                    <button type='submit' class='red-button' name='vote' value='up'> Hilarious! Great joke!
                    </button>
                </form>
                
                <form action='' method='post'>
                    <input type='hidden' name='jokeId' value='{$randomJoke['id']}'>
                    <button type='submit' class='red-button' name='vote' value='down'> That joke wasn't funny. Just awful </button>
                </form>
            </div>"
    );
}



// NOW TO ADD VOTES TO THE JOKES:
if (isset($_POST['vote'])) {
    $action = $_POST['vote'];
    $jokeId = $_POST['jokeId'];
    vote($pdo, $jokeId, $action);
    echo("<p>VOTED " . strtoupper($action) . "</p>");
}

include '../joke_base/inc/footer.inc.php';
?>
