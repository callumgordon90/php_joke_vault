<?php
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php';
?>



<?php

//this sql query selects everything from the user table
$sql = "SELECT * FROM user";

$statement = $pdo->query($sql);

$users = $statement->fetchAll(PDO::FETCH_ASSOC);

//this foreach prints off all of the users
foreach ($users as $user){
   //var_dump($user);
}

?>


<?php 
    //LOGIC TO PROCESS LOGINS: 
    if(isset($_POST['login'])){

        echo("LOGIN FORM SENT");
        

        $e_username = $_POST['username'];
        $e_password = $_POST['password'];
        

        //check the entered credentials against the database:
        $l_sql = "SELECT * from user WHERE username = :username";

        $l_statement = $pdo->prepare($l_sql);

        $l_statement->bindParam(':username', $e_username);
        $l_statement->execute();

        $user = $l_statement->fetch(PDO::FETCH_ASSOC);

        //ERROR DEBUGGING: 
        var_dump($e_username, $e_password, $user['password']);

        // Verify password and perform login logic:
            if($user && password_verify($e_password, $user['password'] )){
                //If the password is correct, perform additional logic:

                //start a session and store user information:
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    //redirect to index.php after succesful login:
                    var_dump("Before header");
                    header('Location: index.php');
                    exit;        
            }
    } else {

        echo('FAILED LOGIN or LOGIN NOT SENT YET');
    }
?>



<h1>LOGIN PAGE OF THE WEBSITE</h1>

<h2>User Login</h2>


<form action="" method="post" enctype="multipart/form-data">
       

        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <!-- Submit Button -->
        <button type="submit" name="login">login</button>
    </form>
    <br>

    
<?php include '../joke_base/inc/footer.inc.php'; ?>