<!-- rating_review_section.php -->
<?php
// This file is included in music.php and video.php to show ratings and reviews
$content_type = isset($music) ? 'music' : 'video';
$content_id = isset($music) ? $music['music_id'] : $video['video_id'];

// Get average rating
$avg_rating_query = "SELECT AVG(rating_value) as avg_rating FROM rating 
                    WHERE content_type = '$content_type' AND content_id = $content_id";
$avg_rating_result = mysqli_query($conn, $avg_rating_query);
$avg_rating = mysqli_fetch_assoc($avg_rating_result);
$avg_rating_value = $avg_rating['avg_rating'] ? round($avg_rating['avg_rating'], 1) : 0;

// Get user's rating if logged in
$user_rating = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_rating_query = "SELECT rating_value FROM rating 
                         WHERE user_id = $user_id AND content_type = '$content_type' AND content_id = $content_id";
    $user_rating_result = mysqli_query($conn, $user_rating_query);
    if (mysqli_num_rows($user_rating_result) > 0) {
        $user_rating = mysqli_fetch_assoc($user_rating_result)['rating_value'];
    }
}

// Get reviews
$reviews_query = "SELECT r.*, u.name, u.username 
                 FROM review r 
                 JOIN users u ON r.user_id = u.user_id 
                 WHERE r.content_type = '$content_type' AND r.content_id = $content_id 
                 ORDER BY r.created_at DESC 
                 LIMIT 3";
$reviews_result = mysqli_query($conn, $reviews_query);
?>

<!-- Rating Section -->
<div class="rating-section mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold">Rating: </span>
            <span class="rating-stars">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php if($i <= floor($avg_rating_value)): ?>
                        <i class="fas fa-star"></i>
                    <?php elseif($i == ceil($avg_rating_value) && fmod($avg_rating_value, 1) >= 0.5): ?>
                        <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                        <i class="far fa-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </span>
            <span class="ms-2">(<?php echo $avg_rating_value; ?>)</span>
        </div>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="rating-container" data-content-id="<?php echo $content_id; ?>" data-content-type="<?php echo $content_type; ?>">
                <span class="fw-bold">Your Rating: </span>
                <div class="d-inline">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="rating-star fas fa-star <?php echo $i <= $user_rating ? 'text-warning' : 'text-muted'; ?>" 
                           data-rating="<?php echo $i; ?>" style="cursor: pointer;"></i>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Reviews Section -->
<div class="reviews-section">
    <h6 class="fw-bold mb-2">Reviews:</h6>
    
    <?php if(mysqli_num_rows($reviews_result) > 0): ?>
        <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
            <div class="review-item border-bottom pb-2 mb-2">
                <div class="d-flex justify-content-between">
                    <strong><?php echo $review['name'] ?: $review['username']; ?></strong>
                    <small class="text-muted"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></small>
                </div>
                <p class="mb-1"><?php echo $review['review_text']; ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted small">No reviews yet.</p>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#reviewModal<?php echo $content_id . $content_type; ?>">
            Add Review
        </button>
        
        <!-- Review Modal -->
        <div class="modal fade" id="reviewModal<?php echo $content_id . $content_type; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="add_review.php" method="POST">
                            <input type="hidden" name="content_type" value="<?php echo $content_type; ?>">
                            <input type="hidden" name="content_id" value="<?php echo $content_id; ?>">
                            <div class="mb-3">
                                <label for="reviewText" class="form-label">Your Review</label>
                                <textarea class="form-control" id="reviewText" name="review_text" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="small text-muted mt-2"><a href="login.php">Login</a> to add a review</p>
    <?php endif; ?>
</div>