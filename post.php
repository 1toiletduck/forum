<?php
// PHP 8 compatible version of post.php

include '../connect.php'; // Ensure connect.php uses mysqli and is updated for PHP 8
session_start();

$topicID = isset($_GET['topicID']) ? (int)$_GET['topicID'] : 0;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];

    if (empty($content)) {
        $error = 'Content cannot be empty.';
    } else {
        // Assuming 'author' is stored in the session and a valid topicID is provided
        $author = $_SESSION['username']; // Update to match your session or authentication logic

        $insertPost = $mysqli->prepare("INSERT INTO km_posts (topicID, author, content, postedDate) VALUES (?, ?, ?, NOW())");
        $insertPost->bind_param("iss", $topicID, $author, $content);
        if (!$insertPost->execute()) {
            $error = 'Failed to post your message.';
        } else {
            header("Location: topic.php?ID=$topicID"); // Redirect to the topic page
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Message</title>
    <style>
        .form-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 600px; /* Adjust as necessary */
            margin: auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Optional styling */
        }
        .form-group {
            margin-bottom: 15px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Post a Message</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="content">Message:</label>
                <textarea name="content" id="content" rows="5" style="width: 100%;"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Post">
            </div>
        </form>
    </div>
</body>
</html>
