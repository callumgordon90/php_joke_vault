<?php
    //NOTE: ONLY OPEN A NEW SESSION IF THERE IS NOT 
    if(session_status() == PHP_SESSION_NONE){
        session_start(); 
    }  
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>THE JOKE VAULT</title>
            <link rel="stylesheet" href="inc/styles.css"  />
            <?php
            // Dynamically set the class based on login status
            $headerClass = isset($_SESSION['user_id']) ? 'logged-in' : 'logged-out';
            $footerClass = isset($_SESSION['user_id']) ? 'logged-in' : 'logged-out';
            ?>
            <style>
            /* Inline style to set the background color dynamically */
            .header-body {
                max-width: 100%;
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                background-color:<?php echo $headerClass === 'logged-in' ? 'rgb(172, 13, 13)' : 'rgb(7, 7, 61)'; ?>;
                }

            .footer-body{
            background-color:<?php echo $footerClass === 'logged-in' ? 'rgb(172, 13, 13)' : 'rgb(7, 7, 61)'; ?>;
            max-width: 100%;
            margin-top: auto; /* This pushes the footer to the bottom */
            }

            </style>

    </head>

    <body>

    <div class="header-body <?php echo isset($_SESSION['user_id']) ? 'logged-in' : 'logged-out'; ?>">
        <h1 class="main-title">THE JOKE VAULT (Proof of Concept)</h1>
        <nav>

        <!-- CHANGE NAME OF HOMEPAGE DEPENDING ON IF A USER IS LOGGED IN-->
        <?php if (!isset($_SESSION['user_id'])):?>
            <a class="nav-text" href="index.php">HOME</a> | 
        <?php else: ?>
            <a class="nav-text" href="index.php"><?php echo("Welcome " . $_SESSION['username']) ?></a> |
        <?php endif; ?>

        <!--IF USER IS LOGGED, THE FOLLOWING HEADERS APPEAR:-->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="nav-text" href="create_joke.php">TELL US A JOKE</a> | 
            <a class="nav-text" href="see_joke.php">SEE A JOKE</a> | 
            <a class="nav-text" href="see_all_jokes.php">JOKE LEAGUE TABLE</a> | 
            <a class="nav-text" href="see_users.php">THE SOCIETY OF JOKERS</a> | 
            <a class="nav-text" href="profile.php">MY PROFILE</a> |

        <?php endif; ?>

            
            <!--<a class="nav-text" href="welcome.php">PLACEHOLDER</a> |-->
            
            <!-- SIGN UP PAGE DISSAPPEARS IF USER IS LOGGED-->
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a class="nav-text" href="register.php">SIGN UP</a> |
            <?php endif; ?> 


            <!-- 'LOGIN' BECOMES 'LOGOUT' WHEN USER IS SIGNED IN-->
            <?php if (isset($_SESSION['user_id'])): ?>
            <a class="nav-text" href="logout.php">SIGN OUT</a> |
            <?php else: ?>
            <a class="nav-text" href="login.php">SIGN IN</a> |
            <?php endif; ?>
        
        </nav>


    </div>


