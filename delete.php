<?php
// PHP 8 compatible version of delete.php

include '../connect.php'; // Ensure connect.php uses mysqli and is updated for PHP 8
session_start();

// Check if the user is logged in and has the appropriate permissions
// This is a placeholder check; adjust according to your authentication system and permissions
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    die("You do not have permission to perform this action.");
}

// Validate input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $type = isset($_GET['type']) ? $_GET['type'] : 'post'; // Determine if deleting a post or a topic

    // Depending on the type, set the appropriate SQL statement
    if ($type === 'post') {
        $sql = "DELETE FROM km_posts WHERE id = ?";
    } elseif ($type === 'topic') {
        $sql = "DELETE FROM km_topics WHERE id = ?";
        // Optionally, include additional logic to delete all posts associated with the topic
    } else {
        die("Invalid delete type specified.");
    }

    // Prepare and execute the query
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Success; redirect or inform the user
            echo "The $type has been successfully deleted.";
            // Redirect to a confirmation page or back to where the user came from
            header("Location: index.php");
            exit;
        } else {
            die("Error executing query: " . $stmt->error);
        }
    } else {
        die("Error preparing query: " . $mysqli->error);
    }
} else {
    die("Invalid request.");
}
?>
