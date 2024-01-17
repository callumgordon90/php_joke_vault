<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>

<h1 class='vault-font'> The League Table of Jokes. Voted from best to worst: </h1>

<?php 

    $sql = "SELECT joke.summary, joke.vote_up, user.name, user.username
            FROM joke
            JOIN user ON joke.user_id = user.id
            ORDER BY joke.vote_up DESC";
            

    $statement = $pdo->query($sql);

    $jokeset = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach($jokeset as $joke){
        echo("<div class='vault-font'>
        <li><br><strong>{$joke['summary']}</strong> by {$joke['name']} AKA {$joke['username']}. No. of votes: {$joke['vote_up']}</li><br></div>" );
    }

    //var_dump($statement);

?>



<?php include '../joke_base/inc/footer.inc.php'; ?>