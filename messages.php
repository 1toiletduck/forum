<?php
// PHP 8 compatible version of messages.php without using tables for layout

include '../connect.php'; // Ensure connect.php uses mysqli
session_start();

// Assuming 'topicID' is passed as a query parameter to identify the topic
$topicID = isset($_GET['topicID']) ? (int)$_GET['topicID'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css" type="text/css">
    <title>Topic Messages</title>
    <style>
        .messages-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Adjust the gap between messages */
        }
        .message-entry {
            display: flex;
            flex-direction: column;
            padding: 10px;
            border: 1px solid #ccc; /* Add border for each message entry */
            border-radius: 5px; /* Optional: rounded corners for the entries */
        }
        .message-header {
            margin-bottom: 10px; /* Space between header and body */
        }
        .message-body {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="content">
        <div><a href='forum.php?ID=<?php echo $topicID; ?>'>Back to Topic</a></div>
        <div class="messages-container">
            <?php
            if ($topicID > 0) {
                // Fetch messages for the specified topic using prepared statements
                $getMessages = $mysqli->prepare("SELECT * FROM km_messages WHERE topicID = ? ORDER BY postedDate ASC");
                $getMessages->bind_param("i", $topicID);
                $getMessages->execute();
                $messages = $getMessages->get_result();

                if ($messages->num_rows > 0) {
                    while ($message = $messages->fetch_assoc()) {
                        echo "<div class='message-entry'>";
                        // Display message author and date (Assuming 'author' and 'postedDate' fields exist)
                        echo "<div class='message-header'>Posted by " . htmlspecialchars($message['author']) . " on " . date("Y-m-d H:i:s", strtotime($message['postedDate'])) . "</div>";
                        // Display message content (Assuming 'content' field exists)
                        echo "<div class='message-body'>" . htmlspecialchars($message['content']) . "</div>";
                        echo "</div>"; // Close message-entry
                    }
                } else {
                    echo "<div>No messages found in this topic.</div>";
                }
            } else {
                echo "<div>Invalid topic ID.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
