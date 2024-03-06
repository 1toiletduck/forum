<?php
// PHP 8 compatible version of combining edit.php and reply.php

include '../connect.php'; // Ensure connect.php uses mysqli
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$messageID = isset($_GET['messageID']) ? (int)$_GET['messageID'] : 0;
$topicID = isset($_GET['topicID']) ? (int)$_GET['topicID'] : 0;

// Initial values
$isEditing = false;
$existingContent = '';

// Determine the action: edit existing message or reply to a topic
if ($action === 'edit' && $messageID > 0) {
    $isEditing = true;
    // Fetch the existing message for editing
    $getMessage = $mysqli->prepare("SELECT content FROM km_messages WHERE ID = ?");
    $getMessage->bind_param("i", $messageID);
    $getMessage->execute();
    $result = $getMessage->get_result();
    if ($message = $result->fetch_assoc()) {
        $existingContent = $message['content'];
    } else {
        die("Message not found.");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    if ($isEditing) {
        // Update the existing message
        $updateMessage = $mysqli->prepare("UPDATE km_messages SET content = ? WHERE ID = ?");
        $updateMessage->bind_param("si", $content, $messageID);
        $updateMessage->execute();
    } else {
        // Insert a new reply into the topic
        $insertReply = $mysqli->prepare("INSERT INTO km_messages (topicID, content, author, postedDate) VALUES (?, ?, ?, NOW())");
        $author = $_SESSION['username']; // Assuming 'username' is stored in the session
        $insertReply->bind_param("iss", $topicID, $content, $author);
        $insertReply->execute();
    }

    // Redirect back to the topic or some confirmation page
    header("Location: topic.php?ID=$topicID");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditing ? 'Edit Message' : 'Reply to Topic'; ?></title>
</head>
<body>
    <form method="post">
        <label for="content"><?php echo $isEditing ? 'Edit your message:' : 'Your reply:'; ?></label>
        <textarea name="content" id="content" rows="5" cols="50"><?php echo htmlspecialchars($existingContent); ?></textarea><br>
        <input type="submit" value="<?php echo $isEditing ? 'Update Message' : 'Post Reply'; ?>">
    </form>
</body>
</html>
