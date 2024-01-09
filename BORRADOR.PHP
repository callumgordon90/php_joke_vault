<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>

<?php 
//First of all, Get ALL the jokes:
$sql = "SELECT joke.id, joke.summary, joke.vote_up, user.name, user.username
            FROM joke
            JOIN user ON joke.user_id = user.id";
            

    $statement = $pdo->query($sql);

    $jokeset = $statement->fetchAll(PDO::FETCH_ASSOC);

    //Now define functions:

    //Function to get random joke from the array: 
    function getRandomJoke($jokeset){
        $randomIndex = array_rand($jokeset);
        return $jokeset[$randomIndex];
    }

    //Function to vote a joke up:
    function voteUp($pdo, $jokeId){
        $sql = "UPDATE joke SET vote_up = vote_up + 1 WHERE id = :jokeId";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':jokeId', $jokeId);
        $statement->execute();
    }

    //Function to vote a joke down: 
    function voteDown($pdo, $jokeId){
        $sql = "UPDATE joke SET vote_up = vote_up - 1 WHERE id = :jokeId";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':jokeId', $jokeId);
        $statement->execute();
    }


?>




<h2> Spin the wheel! Hit the button to see a random joke. </h2>

<!-- button to get a random joke -->
<form action="" method="post">
    <button type="submit" name="getRandomJoke"> COME ON, MAKE ME LAUGH </button>
</form>




<?php  //Print a random joke when a user hits the button: 
    if(isset($_POST['getRandomJoke'])){
        $randomJoke = getRandomJoke($jokeset);

        echo("<h1> {$randomJoke['summary']}</h1><p> by {$randomJoke['name']} AKA {$randomJoke['username']} </p>");

        echo(
            "<h2>Did that make you laugh? Rate the joke: </h2>
            <form action='' method='post'>
            <button type='submit' name='voteUp'value='{$randomJoke['id']}'> Hilarious! Great joke!
            </button>
            </form>
            
            <form action='' method='post'>
            <button type='submit' name='voteDown' value='{$randomJoke['id']}'> That joke wasn't funny. Just awful </button>
            </form>"
        );

        //NOW TO ADD VOTES TO THE JOKES:
        if(isset($_POST['voteUp'])){
            voteUp($pdo, $_POST['voteUp']); 
            echo("<p>VOTED UP</p>"); 
        }

        if(isset($_POST['voteDown'])){
            voteDown($pdo, $_POST['voteDown']);
            echo("<p>VOTED DOWN</p>");
        }
    } 


?>



<?php include '../joke_base/inc/footer.inc.php'; ?>