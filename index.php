<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>


<?php /*
if(isset($_SESSION['user_id'])){

    echo('<h1>' . 'WELCOME ' . $_SESSION['username'] . '</h1>');
    //echo('<h1>' . 'this is your user id:' . $_SESSION['user_id'] . '</h1>');

} else {

    //echo('not logged on ');
    //var_dump($_SESSION);
} */
?>

<div class='welcome'>
    <div class='welcome-text'>

        <h1>WELCOME TO THE JOKE VAULT <?php if(isset($_SESSION['user_id'])) echo($_SESSION['username']) . '!!!'; else echo('!!!');?></h1>

        <h2>THE WEBSITE WHICH HOSTS THE FUNNIEST JOKES</h2>
        <h3>Create funny jokes!</h3>
        <h3>See the funny jokes of others!</h3> 
        <h3>Vote on your favourites!</h3> 

        <?php if(!isset($_SESSION['user_id'])){

            echo('<h3> Become a member today: <a class="no-underline" href="register.php">sign up</a> </h3>
            <h3> OR.. already have an account? <a class="no-underline" href="login.php">login</a> </h3>');
        } else{
            echo("<h2>..take a look around the site!</h2>");
        }
        ?>

    </div>
        <img class='welcome-photo' src='photos\jester.jpg' alt='Site Photo'>
</div>






<?php include '../joke_base/inc/footer.inc.php'; ?>