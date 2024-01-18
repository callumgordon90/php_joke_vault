<?php
    session_start();
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php'; 
?>


<?php 
//$sql = "SELECT * FROM `user`";
//$statement = $pdo->query($sql);

/////////////////////////////////////////
// Set the number of users to display per page
$usersPerPage = 12;

// Get the current page number from the URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for fetching users based on the current page
$offset = ($currentPage - 1) * $usersPerPage;

// SQL query to fetch users with pagination
$sql = "SELECT * FROM `user` ORDER BY user.name LIMIT :offset, :usersPerPage";
$statement = $pdo->prepare($sql);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$statement->bindParam(':usersPerPage', $usersPerPage, PDO::PARAM_INT);
$statement->execute();

// Fetch all users
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch total number of users for pagination
$totalUsers = $pdo->query("SELECT COUNT(*) FROM `user`")->fetchColumn();

// Calculate total number of pages
$totalPages = ceil($totalUsers / $usersPerPage);




/////////////////////////////////////////



//fetch all users:
//$users = $statement->fetchAll(PDO::FETCH_ASSOC);

//var_dump($sql[users]);
?>

<h1 class='vault-font'>LIST OF JOKE VAULT JOKERS!</h1>


<ul class="user-list">
        <?php foreach ($users as $user) : ?>
        <li class="user-item">
            <?php
            if(!empty($user['photo'])){
                echo ('<img class="user-photo" src="../joke_base/photos/' . $user['photo'] . '" alt="User Photo">');
            }
                
            echo '<div class="joker"><a href="stranger_profile.php?user_id=' . $user['id'] . '">' . $user['name'] . '</a></div>';
            ?>
        </li>
        <?php endforeach; ?>
</ul>



<?php 
// Display pagination links
echo '<div class="pagination">Continued on the next pages..: ';
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<a href="see_users.php?page=' . $i . '">' . $i . '</a>';
}
echo '</div>';




include '../joke_base/inc/footer.inc.php'; ?>