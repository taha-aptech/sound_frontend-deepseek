<!-- video.php -->
<?php
include 'header.php';

// Get search parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$genre = isset($_GET['genre']) ? intval($_GET['genre']) : 0;
$artist = isset($_GET['artist']) ? intval($_GET['artist']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : 0;

// Build query
$videos_query = "SELECT v.*, a.artist_name, g.genre_name, al.album_name, l.language_name 
                FROM video v 
                LEFT JOIN artist a ON v.artist_id = a.artist_id 
                LEFT JOIN genre g ON v.genre_id = g.genre_id 
                LEFT JOIN album al ON v.album_id = al.album_id 
                LEFT JOIN language l ON v.language_id = l.language_id 
                WHERE 1=1";

if (!empty($search)) {
    $videos_query .= " AND (v.title LIKE '%$search%' OR a.artist_name LIKE '%$search%' OR al.album_name LIKE '%$search%')";
}

if ($genre > 0) {
    $videos_query .= " AND v.genre_id = $genre";
}

if ($artist > 0) {
    $videos_query .= " AND v.artist_id = $artist";
}

if ($year > 0) {
    $videos_query .= " AND v.year = $year";
}

$videos_query .= " ORDER BY v.created_at DESC";
$videos_result = mysqli_query($conn, $videos_query);

// Get filters for dropdowns
$genres = mysqli_query($conn, "SELECT * FROM genre ORDER BY genre_name");
$artists = mysqli_query($conn, "SELECT * FROM artist ORDER BY artist_name");
$years = mysqli_query($conn, "SELECT DISTINCT year FROM video ORDER BY year DESC");
?>

<div class="container py-5">
    <h1 class="mb-4" data-aos="fade-right">Video Library</h1>
    
    <!-- Search and Filter Section -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <form method="GET" action="video.php">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search videos..." value="<?php echo $search; ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="genre" class="form-select">
                            <option value="0">All Genres</option>
                            <?php while($g = mysqli_fetch_assoc($genres)): ?>
                                <option value="<?php echo $g['genre_id']; ?>" <?php echo $genre == $g['genre_id'] ? 'selected' : ''; ?>>
                                    <?php echo $g['genre_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="artist" class="form-select">
                            <option value="0">All Artists</option>
                            <?php while($a = mysqli_fetch_assoc($artists)): ?>
                                <option value="<?php echo $a['artist_id']; ?>" <?php echo $artist == $a['artist_id'] ? 'selected' : ''; ?>>
                                    <?php echo $a['artist_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="year" class="form-select">
                            <option value="0">All Years</option>
                            <?php while($y = mysqli_fetch_assoc($years)): ?>
                                <option value="<?php echo $y['year']; ?>" <?php echo $year == $y['year'] ? 'selected' : ''; ?>>
                                    <?php echo $y['year']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Videos Grid -->
    <div class="row">
        <?php if(mysqli_num_rows($videos_result) > 0): ?>
            <?php while($video = mysqli_fetch_assoc($videos_result)): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card video-card h-100">
                        <?php if($video['is_new']): ?>
                            <span class="new-badge">NEW</span>
                        <?php endif; ?>
                        <img src="<?php echo $video['thumbnail_img'] ?: 'images/thumbnail_img/default-video.jpg'; ?>" class="card-img-top" alt="<?php echo $video['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $video['title']; ?></h5>
                            <p class="card-text text-muted"><?php echo $video['artist_name']; ?></p>
                            <p class="card-text small"><?php echo $video['album_name'] ? 'Album: ' . $video['album_name'] : ''; ?></p>
                            <p class="card-text small text-muted">
                                Genre: <?php echo $video['genre_name']; ?> | 
                                Year: <?php echo $video['year']; ?> |
                                Language: <?php echo $video['language_name']; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="<?php echo $video['file_path']; ?>" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-play me-2"></i>Watch Video
                                </a>
                                <span class="badge bg-secondary"><?php echo $video['language_name']; ?></span>
                            </div>
                            
                            <!-- Ratings and Reviews Section -->
                            <div class="mt-3">
                                <?php 
                                // Set variables for rating_review_section.php
                                $content_type = 'video';
                                $content_id = $video['video_id'];
                                include 'rating_review_section.php'; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <h3>No videos found</h3>
                <p>Try adjusting your search criteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>