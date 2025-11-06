<!-- profile.php -->
<?php
include 'header.php';
// include 'auth_check.php';

// // Ensure user is logged in
// requireLogin();

$user_id = $_SESSION['user_id'];

// Get user details
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Get user's ratings count
$ratings_count_query = "SELECT COUNT(*) as total_ratings FROM rating WHERE user_id = $user_id";
$ratings_count_result = mysqli_query($conn, $ratings_count_query);
$ratings_count = mysqli_fetch_assoc($ratings_count_result)['total_ratings'];

// Get user's reviews count
$reviews_count_query = "SELECT COUNT(*) as total_reviews FROM review WHERE user_id = $user_id";
$reviews_count_result = mysqli_query($conn, $reviews_count_query);
$reviews_count = mysqli_fetch_assoc($reviews_count_result)['total_reviews'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $errors = [];
    
    // Check if email already exists (excluding current user)
    $email_check = "SELECT user_id FROM users WHERE email = '$email' AND user_id != $user_id";
    $email_result = mysqli_query($conn, $email_check);
    if (mysqli_num_rows($email_result) > 0) {
        $errors[] = "Email already exists";
    }
    
    // Check if username already exists (excluding current user)
    $username_check = "SELECT user_id FROM users WHERE username = '$username' AND user_id != $user_id";
    $username_result = mysqli_query($conn, $username_check);
    if (mysqli_num_rows($username_result) > 0) {
        $errors[] = "Username already taken";
    }
    
    if (empty($errors)) {
        $update_query = "UPDATE users SET 
                        name = '$name', 
                        username = '$username', 
                        email = '$email', 
                        phone = '$phone', 
                        address = '$address' 
                        WHERE user_id = $user_id";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['name'] = $name;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $success = "Profile updated successfully!";
            
            // Refresh user data
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);
        } else {
            $errors[] = "Error updating profile: " . mysqli_error($conn);
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors_password = [];
    
    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        $errors_password[] = "Current password is incorrect";
    }
    
    if ($new_password !== $confirm_password) {
        $errors_password[] = "New passwords do not match";
    }
    
    if (strlen($new_password) < 6) {
        $errors_password[] = "New password must be at least 6 characters long";
    }
    
    if (empty($errors_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $password_update_query = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";
        
        if (mysqli_query($conn, $password_update_query)) {
            $success_password = "Password changed successfully!";
        } else {
            $errors_password[] = "Error changing password: " . mysqli_error($conn);
        }
    }
}

// Get user's recent activity
$recent_ratings_query = "SELECT r.*, 
                        CASE 
                            WHEN r.content_type = 'music' THEN m.title
                            WHEN r.content_type = 'video' THEN v.title
                        END as content_title,
                        CASE 
                            WHEN r.content_type = 'music' THEN 'Music'
                            WHEN r.content_type = 'video' THEN 'Video'
                        END as content_type_name
                        FROM rating r
                        LEFT JOIN music m ON r.content_type = 'music' AND r.content_id = m.music_id
                        LEFT JOIN video v ON r.content_type = 'video' AND r.content_id = v.video_id
                        WHERE r.user_id = $user_id
                        ORDER BY r.created_at DESC
                        LIMIT 5";
$recent_ratings_result = mysqli_query($conn, $recent_ratings_query);

$recent_reviews_query = "SELECT r.*, 
                        CASE 
                            WHEN r.content_type = 'music' THEN m.title
                            WHEN r.content_type = 'video' THEN v.title
                        END as content_title,
                        CASE 
                            WHEN r.content_type = 'music' THEN 'Music'
                            WHEN r.content_type = 'video' THEN 'Video'
                        END as content_type_name
                        FROM review r
                        LEFT JOIN music m ON r.content_type = 'music' AND r.content_id = m.music_id
                        LEFT JOIN video v ON r.content_type = 'video' AND r.content_id = v.video_id
                        WHERE r.user_id = $user_id
                        ORDER BY r.created_at DESC
                        LIMIT 5";
$recent_reviews_result = mysqli_query($conn, $recent_reviews_query);
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4" data-aos="fade-right">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background-color: var(--accent-color);">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="card-title"><?php echo $user['name']; ?></h5>
                    <p class="text-muted small">@<?php echo $user['username']; ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-0" style="color: var(--primary-color);"><?php echo $ratings_count; ?></h6>
                                <small class="text-muted">Ratings</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-0" style="color: var(--primary-color);"><?php echo $reviews_count; ?></h6>
                                <small class="text-muted">Reviews</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="text-start small">
                        <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i><?php echo $user['email']; ?></p>
                        <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i><?php echo $user['phone']; ?></p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i><?php echo $user['address']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">Member Since</h6>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-calendar me-2"></i>
                        <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9" data-aos="fade-left">
            <!-- Profile Update Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: var(--primary-color);">
                    <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Update Profile</h5>
                </div>
                <div class="card-body">
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
                    
                    <form method="POST" action="profile.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo $user['name']; ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo $user['username']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo $user['email']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo $user['phone']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $user['address']; ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header  text-white" style="background-color: var(--accent-color); color: var(--dark-color);">
                    <h5 class="card-title mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($success_password)): ?>
                        <div class="alert alert-success"><?php echo $success_password; ?></div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors_password)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors_password as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="profile.php">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn text-white" style="background-color: var(--accent-color); color: var(--dark-color);">Change Password</button>
                    </form>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row">
                <!-- Recent Ratings -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="fas fa-star me-2 text-warning"></i>Recent Ratings</h6>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($recent_ratings_result) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php while($rating = mysqli_fetch_assoc($recent_ratings_result)): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 small"><?php echo $rating['content_title']; ?></h6>
                                                    <small class="text-muted"><?php echo $rating['content_type_name']; ?></small>
                                                </div>
                                                <div class="text-warning">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star<?php echo $i <= $rating['rating_value'] ? '' : '-o'; ?> small"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo date('M j, Y', strtotime($rating['created_at'])); ?></small>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">No ratings yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Reviews -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="fas fa-comment me-2 text-info"></i>Recent Reviews</h6>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($recent_reviews_result) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php while($review = mysqli_fetch_assoc($recent_reviews_result)): ?>
                                        <div class="list-group-item px-0">
                                            <h6 class="mb-1 small"><?php echo $review['content_title']; ?></h6>
                                            <p class="mb-1 small"><?php echo substr($review['review_text'], 0, 50); ?>...</p>
                                            <small class="text-muted"><?php echo $review['content_type_name']; ?> • <?php echo date('M j, Y', strtotime($review['created_at'])); ?></small>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">No reviews yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <a href="music.php" class="btn  btn-sm w-100" style="border-color: var(--accent-color);
color: var(--accent-color);">
                                <i class="fas fa-music fa-2x mb-2"></i><br>
                                Browse Music
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="video.php" class="btn  btn-sm w-100" style="border-color: var(--accent-color);
color: var(--accent-color);">
                                <i class="fas fa-video fa-2x mb-2"></i><br>
                                Watch Videos
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="artist.php" class="btn  btn-sm w-100" style="border-color: var(--accent-color);
color: var(--accent-color);">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Explore Artists
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="feedback.php" class="btn  btn-sm w-100" style="border-color: var(--accent-color);
color: var(--accent-color);">
                                <i class="fas fa-comment-dots fa-2x mb-2"></i><br>
                                Give Feedback
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>