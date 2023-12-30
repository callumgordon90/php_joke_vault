<?php
    require '../joke_base/inc/auth.inc.php';
    require '../joke_base/inc/header.inc.php';    
?>

<h1>REGISTER PAGE </h1>
<h2>User Registration</h2>

    <form action="register.php" method="post" enctype="multipart/form-data">
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

        <!-- Photo -->
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>
        <br>

        <!-- Submit Button -->
        <button type="submit">Register</button>
    </form>
    <br>


    <h1> already have an account? <a href="login.php">Login</a> </h1>










<?php include '../joke_base/inc/footer.inc.php'; ?>