<?php
    include '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php';  


// register.php 
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    if (isset($_POST['register'])) {
        echo("hi");
        // Retrieve form data
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
    
        // Perform validation if needed


        // Check if the user has chosen to upload a photo, and if not provide a default one:
        if (!empty($_FILES['photo']['name'])){
            $photo = $_FILES['photo']['name'];
            //Handle the file upload:
            move_uploaded_file($_FILES['photo']['tmp_name'], '../joke_base/photos/' . $photo);
        } else {
            // Use a default photo if the user hasnt chosen a photo:
                $photo = 'jester.jpg';
        }

        // Insert data into the database
        $sql = "INSERT INTO user (`name`, `username`, `password`, `photo`) VALUES (:name, :username, :password, :photo)";
        $statement = $pdo->prepare($sql);
        //bind parameters
        $statement->bindParam(':name', $name);
        $statement->bindParam(':username', $username);
       
       

        // Hash the password (for security)
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $statement->bindParam(':password', $hashedPassword);

        $statement->bindParam(':photo', $photo);

        // Execute the statement
        $statement->execute();

       

        var_dump($statement);
        
        //start a session
        session_start();

        // Retrieve the user ID after insertion
        $user_id = $pdo->lastInsertId();

        // Store user information in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['photo'] = $_FILES['photo']['name'];

        // Redirect the user after successful registration
        header("Location: index.php");
        exit();
        }
        else {

            //echo("not registered");
        }
        ?>

<div class='welcome'>
    <div class='welcome-text'>

        <h1>REGISTER PAGE </h1>
        <h2>User Registration</h2>

            <form action="" method="post" enctype="multipart/form-data">
                <!-- Name -->
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br>

                <!-- Username -->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br>

                <!-- Password -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>

                <!-- Photo  -->
                <label for="photo">Photo:</label>
                <input type="file"  id="photo" name="photo" accept="image/*">
                
                <br>
                <!-- Submit Button -->
                <button type="submit" class='red-button' name="register">Register</button>
            </form>
            <br>

            <h3> ..Already have an account? <a href="login.php">login</a> </h3>
    </div>
        <img class='welcome-photo' src='photos\jester.jpg' alt='Site Photo'>
</div>
        

<?php include '../joke_base/inc/footer.inc.php'; ?>