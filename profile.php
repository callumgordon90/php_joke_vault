<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>

<?php
    if(isset($_SESSION['user_id'])){
        
        $sql = "SELECT * FROM `user` WHERE user.id = :user_id";

        //Prepare the sql statement
        $statement = $pdo->prepare($sql);

        $statement->bindParam(':user_id', $_SESSION['user_id']);
        $statement->execute();

        //Fetch the user 
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        //var_dump($user);

        
            echo(
                "<h1>Name: {$user['name']}</h1>" .
                "<h1>Alias: {$user['username']} </h1>" .
                "<img class='profile-photo' src='../joke_base/photos/{$user['photo']}' alt='User Photo'>"
            );
        


    }
?>








<h1> Edit profile here here here:</h1>




<?php include '../joke_base/inc/footer.inc.php'; ?>