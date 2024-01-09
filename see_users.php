<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>


<?php 
$sql = "SELECT * FROM `user`";

$statement = $pdo->query($sql);


//fetch all users:
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

//var_dump($sql[users]);
?>

<h1>LIST OF JOKE VAULT JOKERS!</h1>


<ul class="user-list">
        <?php foreach ($users as $user) : ?>
        <li class="user-item">
            <?php
            if(!empty($user['photo'])){
                echo ('<img class="user-photo" src="../joke_base/photos/' . $user['photo'] . '" alt="User Photo">');
            }
                echo "<div class='joker'>{$user['name']}</div>";
            ?>
        </li>
        <?php endforeach; ?>
</ul>




<?php include '../joke_base/inc/footer.inc.php'; ?>