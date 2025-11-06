<!-- add_review.php -->
<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['loggedin'])) {
    $_SESSION['error'] = 'Please login to add reviews';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content_id = intval($_POST['content_id']);
    $content_type = mysqli_real_escape_string($conn, $_POST['content_type']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);
    
    // Validate review text
    if (empty(trim($review_text))) {
        $_SESSION['error'] = 'Review text cannot be empty';
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
    
    if (strlen($review_text) > 500) {
        $_SESSION['error'] = 'Review text too long (max 500 characters)';
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
    
    // Insert review
    $insert_query = "INSERT INTO review (user_id, content_type, content_id, review_text, created_at, updated_at) 
                    VALUES ($user_id, '$content_type', $content_id, '$review_text', NOW(), NOW())";
    
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = 'Review added successfully!';
    } else {
        $_SESSION['error'] = 'Error adding review: ' . mysqli_error($conn);
    }
    
    // Redirect back to previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>