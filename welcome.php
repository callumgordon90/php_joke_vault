<?php
    include '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
    
?>

<?php 
$sql = "SELECT * FROM `user`";

$statement = $pdo->query($sql);


//fetch all users:
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

//var_dump($sql[users]);
?>

<div class='welcome'>
    <div class='welcome-text'>
        <h1>WELCOME TO THE JOKE VAULT!</h1>

        <h1>Here are the users </h1>

        <ul>
            <?php foreach ($users as $user) : ?>
                <li><?php echo $user['name']; ?></li>
                <?php if (!empty($user['photo'])) : ?>
                    <li><img src="../joke_base/photos/<?php echo $user['photo']; ?>" alt="User Photo"></li>
                    <?php endif; ?>
                <?php endforeach; ?>
        </ul>
        
    </div>
</div>





<?php include '../joke_base/inc/footer.inc.php'; ?>




