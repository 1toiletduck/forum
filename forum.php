<?php
// PHP 8 compatible version of forum.php without using tables for layout

include '../connect.php'; // Ensure connect.php uses mysqli
session_start();

// Assuming 'ID' is passed as a query parameter to identify the forum
$forumID = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css" type="text/css">
    <title>Forum Topics</title>
    <style>
        .topics-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Adjust the gap between topics */
        }
        .topic-entry {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #ccc; /* Add border for each topic entry */
            border-radius: 5px; /* Optional: rounded corners for the entries */
        }
        .topic-info, .topic-stats {
            display: flex;
            align-items: center;
        }
        .topic-details {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="content">
        <div><a href='../index.php'>Back to Main game</a></div>
        <div class="topics-container">
            <?php
            if ($forumID > 0) {
                // Fetch topics for the specified forum using prepared statements
                $getTopics = $mysqli->prepare("SELECT * FROM km_topics WHERE forumID = ? ORDER BY lastPost DESC");
                $getTopics->bind_param("i", $forumID);
                $getTopics->execute();
                $topics = $getTopics->get_result();

                if ($topics->num_rows > 0) {
                    while ($topic = $topics->fetch_assoc()) {
                        echo "<div class='topic-entry'>";
                        echo "<div class='topic-info'>";
                        // Display topic title and author (Assuming 'title' and 'author' fields exist)
                        echo "<div class='topic-details'><a href='topic.php?ID=" . htmlspecialchars($topic['ID']) . "'>" . htmlspecialchars($topic['title']) . "</a> by " . htmlspecialchars($topic['author']) . "</div>";
                        echo "</div>"; // Close topic-info
                        // Display topic stats (Assuming 'replies' and 'views' fields exist)
                        echo "<div class='topic-stats'>Replies: " . htmlspecialchars($topic['replies']) . " | Views: " . htmlspecialchars($topic['views']) . "</div>";
                        echo "</div>"; // Close topic-entry
                    }
                } else {
                    echo "<div>No topics found in this forum.</div>";
                }
            } else {
                echo "<div>Invalid forum ID.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
