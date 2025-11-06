<!-- feedback.php -->
<?php
include 'header.php';
// include 'auth_check.php';

// // Ensure user is logged in
// requireLogin();

$user_id = $_SESSION['user_id'];

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback_type = mysqli_real_escape_string($conn, $_POST['feedback_type']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);
    $suggestions = mysqli_real_escape_string($conn, $_POST['suggestions']);
    $contact_permission = isset($_POST['contact_permission']) ? 1 : 0;
    
    $errors = [];
    
    // Validation
    if (empty($feedback_type)) {
        $errors[] = "Please select feedback type";
    }
    
    if (empty($comments)) {
        $errors[] = "Please provide your comments";
    }
    
    if (empty($errors)) {
        // Insert feedback into database (you'll need to create this table)
        $insert_query = "INSERT INTO user_feedback (user_id, feedback_type, rating, comments, suggestions, contact_permission, created_at) 
                        VALUES ($user_id, '$feedback_type', $rating, '$comments', '$suggestions', $contact_permission, NOW())";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = "Thank you for your feedback! We appreciate your input.";
            
            // Clear form fields
            $feedback_type = $rating = $comments = $suggestions = '';
            $contact_permission = 0;
        } else {
            $errors[] = "Sorry, there was an error submitting your feedback. Please try again.";
        }
    }
}

// Get user's previous feedback
$previous_feedback_query = "SELECT * FROM user_feedback WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$previous_feedback_result = mysqli_query($conn, $previous_feedback_query);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold mb-3">Share Your Feedback</h1>
            <p class="lead">Help us improve SOUND Entertainment by sharing your thoughts and suggestions</p>
        </div>
    </div>

    <div class="row">
        <!-- Feedback Form -->
        <div class="col-lg-8 mb-5" data-aos="fade-right">
            <div class="card shadow">
                <div class="card-header text-white" style="background-color: var(--primary-color);">
                    <h5 class="card-title mb-0"><i class="fas fa-comment-dots me-2"></i>Submit Feedback</h5>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="feedback.php">
                        <!-- Feedback Type -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Type of Feedback *</label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="form-check card h-100">
                                        <input class="form-check-input" type="radio" name="feedback_type" id="general" value="general" 
                                               <?php echo (isset($_POST['feedback_type']) && $_POST['feedback_type'] == 'general') ? 'checked' : ''; ?> required>
                                        <label class="form-check-label card-body" for="general">
                                            <i class="fas fa-comment fa-2x mb-2 text-primary"></i>
                                            <h6 class="card-title">General Feedback</h6>
                                            <p class="card-text small text-muted">Overall experience and suggestions</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check card h-100">
                                        <input class="form-check-input" type="radio" name="feedback_type" id="bug" value="bug"
                                               <?php echo (isset($_POST['feedback_type']) && $_POST['feedback_type'] == 'bug') ? 'checked' : ''; ?>>
                                        <label class="form-check-label card-body" for="bug">
                                            <i class="fas fa-bug fa-2x mb-2 text-danger"></i>
                                            <h6 class="card-title">Bug Report</h6>
                                            <p class="card-text small text-muted">Report technical issues or errors</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check card h-100">
                                        <input class="form-check-input" type="radio" name="feedback_type" id="feature" value="feature"
                                               <?php echo (isset($_POST['feedback_type']) && $_POST['feedback_type'] == 'feature') ? 'checked' : ''; ?>>
                                        <label class="form-check-label card-body" for="feature">
                                            <i class="fas fa-lightbulb fa-2x mb-2 text-warning"></i>
                                            <h6 class="card-title">Feature Request</h6>
                                            <p class="card-text small text-muted">Suggest new features or improvements</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Overall Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Overall Rating</label>
                            <div class="rating-stars-large">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" 
                                           <?php echo (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : ''; ?>>
                                    <label for="star<?php echo $i; ?>" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <!-- Comments -->
                        <div class="mb-4">
                            <label for="comments" class="form-label fw-bold">Your Comments *</label>
                            <textarea class="form-control" id="comments" name="comments" rows="5" 
                                      placeholder="Please share your detailed feedback, experience, or any issues you encountered..." 
                                      required><?php echo isset($_POST['comments']) ? $_POST['comments'] : ''; ?></textarea>
                        </div>
                        
                        <!-- Suggestions -->
                        <div class="mb-4">
                            <label for="suggestions" class="form-label fw-bold">Suggestions for Improvement</label>
                            <textarea class="form-control" id="suggestions" name="suggestions" rows="3" 
                                      placeholder="Any specific suggestions to make SOUND Entertainment better?"><?php echo isset($_POST['suggestions']) ? $_POST['suggestions'] : ''; ?></textarea>
                        </div>
                        
                        <!-- Contact Permission -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="contact_permission" name="contact_permission"
                                       <?php echo (isset($_POST['contact_permission']) && $_POST['contact_permission']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="contact_permission">
                                    I give permission to contact me regarding this feedback
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4" data-aos="fade-left">
            <!-- User Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background-color: var(--accent-color); color: white;">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                    </div>
                    <h6><?php echo $_SESSION['username']; ?></h6>
                    <p class="text-muted small"></p>
                </div>
            </div>
            
            <!-- Previous Feedback -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-history me-2"></i>Your Previous Feedback</h6>
                </div>
                <div class="card-body">
                    <?php if(mysqli_num_rows($previous_feedback_result) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php while($feedback = mysqli_fetch_assoc($previous_feedback_result)): ?>
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge 
                                            <?php echo $feedback['feedback_type'] == 'general' ? 'bg-primary' : ''; ?>
                                            <?php echo $feedback['feedback_type'] == 'bug' ? 'bg-danger' : ''; ?>
                                            <?php echo $feedback['feedback_type'] == 'feature' ? 'bg-warning' : ''; ?>">
                                            <?php echo ucfirst($feedback['feedback_type']); ?>
                                        </span>
                                        <?php if($feedback['rating'] > 0): ?>
                                            <div class="text-warning small">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star<?php echo $i <= $feedback['rating'] ? '' : '-o'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="small mb-1"><?php echo substr($feedback['comments'], 0, 80); ?>...</p>
                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($feedback['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">No previous feedback</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Feedback Tips -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Feedback Tips</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small">
                        <ul class="mb-0 ps-3">
                            <li>Be specific and detailed in your comments</li>
                            <li>Include steps to reproduce bugs</li>
                            <li>Suggest practical feature improvements</li>
                            <li>We read all feedback and appreciate your time</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars-large {
    display: flex;
    gap: 5px;
}

.rating-stars-large input[type="radio"] {
    display: none;
}

.rating-stars-large .star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-stars-large input[type="radio"]:checked ~ .star-label,
.rating-stars-large .star-label:hover,
.rating-stars-large .star-label:hover ~ .star-label {
    color: #FFD700;
}

.rating-stars-large input[type="radio"]:checked + .star-label {
    color: #FFD700;
}

.form-check .card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.form-check-input:checked + .card-label .card {
    border-color: var(--primary-color);
    background-color: rgba(22, 71, 106, 0.05);
}

.form-check .card:hover {
    border-color: var(--accent-color);
}
</style>

<?php include 'footer.php'; ?>