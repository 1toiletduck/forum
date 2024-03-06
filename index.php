<?php
// PHP 8 compatible version of forums/index.php without using tables for layout

include '../connect.php'; // Ensure connect.php uses mysqli
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css" type="text/css">
    <title>Forum Index</title>
    <style>
        .forum-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Adjust gap between forum entries */
        }
        .forum-entry {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .forum-info {
            display: flex;
            align-items: center;
            gap: 10px; /* Adjust gap between icon and forum info */
        }
        .forum-details, .last-post {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="content">
        <?php
        if (isset($_SESSION['player'])) {
            $playername = $_SESSION['player'];
            // Database operations and logic remain unchanged
            // ...

            echo "<div><a href='../index.php'>Back to Main game</a></div>";
            echo "<div class='forum-container'>";
            // Fetch forums and iterate through them
            while ($forum = $forums->fetch_assoc()) {
                echo "<div class='forum-entry'>";
                echo "<div class='forum-info'>";
                if ($forum['realtimelastpost'] > $user['oldtime']) {
                    echo "<img src='../images/postforum.jpg' border='0'>";
                } else {
                    echo "<img src='../images/postforum.gif' border='0'>";
                }
                echo "<div class='forum-details'>";
                echo "<a href='forum.php?ID=" . htmlspecialchars($forum['forumID']) . "'>" . htmlspecialchars($forum['forumname']) . "</a><br>" . htmlspecialchars($forum['descrip']);
                echo "</div></div>"; // Close forum-info and forum-details
                echo "<div class='last-post'>";
                echo "Topics: " . htmlspecialchars($forum['numtopics']) . " | Posts: " . htmlspecialchars($forum['numposts']) . "<br>Last Post by <b>" . htmlspecialchars($forum['lastposter']) . "</b> at " . htmlspecialchars($forum['timelastpost']);
                echo "</div>"; // Close last-post
                echo "</div>"; // Close forum-entry
            }
            echo "</div>"; // Close forum-container
        } else {
            echo "You are not logged in, please <a href='../login.php'>Login</a>";
        }
        ?>
    </div>
</body>
</html>
