<?php
session_start();
require '../joke_base/inc/auth.inc.php';
require '../joke_base/inc/header.inc.php'; 

?>

<h3 class='vault-font'>LEAGUE TABLE OF JOKES. The best and the worst.. Voted for by members</h3>


<?php 

// Set the number of jokes to display per page
$jokesPerPage = 4;

// Get the current page number from the URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for fetching jokes based on the current page
$offset = ($currentPage - 1) * $jokesPerPage;

// SQL query to fetch jokes with pagination
$sql = "SELECT joke.summary, joke.vote_up, user.name, user.username
        FROM joke
        JOIN user ON joke.user_id = user.id
        ORDER BY joke.vote_up DESC 
        LIMIT :offset, :jokesPerPage";

$statement = $pdo->prepare($sql);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$statement->bindParam(':jokesPerPage', $jokesPerPage, PDO::PARAM_INT);
$statement->execute();

$jokeset = $statement->fetchAll(PDO::FETCH_ASSOC);

    ///////////////////////////////
    // Calculate rank based on the total number of jokes, current page, and offset
    $rank = ($currentPage - 1) * $jokesPerPage + 1;

        foreach ($jokeset as $joke) {
            echo "<div class='joke-container'>";
                
                echo "<div class='joke-rank'>Rank {$rank}<br></div>";

                echo "<div class='joke-line'>";
                echo "<p class='joke-phrase'>{$joke['summary']} by {$joke['name']} AKA {$joke['username']}.
                </p>" . 
                "<div class='joke-votes'>Score: {$joke['vote_up']} </div><br>";
                echo "</div>";

            echo "</div>";

            // Increment rank for the next joke
            $rank++;
        }


// Fetch total number of jokes for pagination
$totalJokes = $pdo->query("SELECT COUNT(*) FROM `joke`")->fetchColumn();

// Calculate total number of pages
$totalPages = ceil($totalJokes / $jokesPerPage);

// Display pagination links
echo '<div class="pagination"><p class="vault-font">Continued on the next pages..: ';
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<a href="see_all_jokes.php?page=' . $i . '">' . $i . '</a>';
}
echo '</div>';

?>

<?php include '../joke_base/inc/footer.inc.php'; ?>
