<!-- genre.php -->
<?php
include 'header.php';

// Get genre ID from URL
$genre_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($genre_id > 0) {
    // Single genre view
    $genre_query = "SELECT * FROM genre WHERE genre_id = $genre_id";
    $genre_result = mysqli_query($conn, $genre_query);
    $genre = mysqli_fetch_assoc($genre_result);
    
    if ($genre) {
        // Get genre's music
        $music_query = "SELECT m.*, a.artist_name, al.album_name 
                       FROM music m 
                       LEFT JOIN artist a ON m.artist_id = a.artist_id 
                       LEFT JOIN album al ON m.album_id = al.album_id 
                       WHERE m.genre_id = $genre_id 
                       ORDER BY m.created_at DESC 
                       LIMIT 10";
        $music_result = mysqli_query($conn, $music_query);
        
        // Get genre's videos
        $videos_query = "SELECT v.*, a.artist_name, al.album_name 
                        FROM video v 
                        LEFT JOIN artist a ON v.artist_id = a.artist_id 
                        LEFT JOIN album al ON v.album_id = al.album_id 
                        WHERE v.genre_id = $genre_id 
                        ORDER BY v.created_at DESC 
                        LIMIT 10";
        $videos_result = mysqli_query($conn, $videos_query);
        ?>
        
        <div class="container py-5">
            <!-- Genre Header -->
            <div class="row mb-4" data-aos="fade-up">
                <div class="col-12 text-center">
                    <h1 class="display-5 fw-bold"><?php echo $genre['genre_name']; ?> Genre</h1>
                    <p class="lead">Explore all <?php echo $genre['genre_name']; ?> music and videos</p>
                </div>
            </div>
            
            <!-- Genre's Music -->
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4"><?php echo $genre['genre_name']; ?> Music</h2>
                    <?php if(mysqli_num_rows($music_result) > 0): ?>
                        <div class="row">
                            <?php while($music = mysqli_fetch_assoc($music_result)): ?>
                                <div class="col-lg-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h5 class="card-title mb-1"><?php echo $music['title']; ?></h5>
                                                    <p class="card-text text-muted small mb-1">
                                                        <?php echo $music['artist_name']; ?> | 
                                                        <?php echo $music['album_name'] ? 'Album: ' . $music['album_name'] : 'Single'; ?>
                                                    </p>
                                                </div>
                                                <?php if($music['is_new']): ?>
                                                    <span class="badge bg-danger">NEW</span>
                                                <?php endif; ?>
                                            </div>
                                            <audio controls class="w-100 mt-2">
                                                <source src="<?php echo $music['file_path']; ?>" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="music.php?genre=<?php echo $genre_id; ?>" class="btn btn-primary">View All <?php echo $genre['genre_name']; ?> Music</a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No music available in this genre.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Genre's Videos -->
            <div class="row" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4"><?php echo $genre['genre_name']; ?> Videos</h2>
                    <?php if(mysqli_num_rows($videos_result) > 0): ?>
                        <div class="row">
                            <?php while($video = mysqli_fetch_assoc($videos_result)): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card video-card h-100">
                                        <?php if($video['is_new']): ?>
                                            <span class="new-badge">NEW</span>
                                        <?php endif; ?>
                                        <img src="<?php echo $video['thumbnail_img'] ?: 'images/thumbnail_img/default-video.jpg'; ?>" 
                                             class="card-img-top" alt="<?php echo $video['title']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $video['title']; ?></h5>
                                            <p class="card-text text-muted small"><?php echo $video['artist_name']; ?></p>
                                            <a href="<?php echo $video['file_path']; ?>" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-1"></i>Watch
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="video.php?genre=<?php echo $genre_id; ?>" class="btn btn-primary">View All <?php echo $genre['genre_name']; ?> Videos</a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No videos available in this genre.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container py-5 text-center"><h2>Genre not found</h2></div>';
    }
} else {
    // Genres listing page
    $genres_query = "SELECT g.*, 
                    (SELECT COUNT(*) FROM music WHERE genre_id = g.genre_id) as music_count,
                    (SELECT COUNT(*) FROM video WHERE genre_id = g.genre_id) as video_count
                    FROM genre g 
                    ORDER BY g.genre_name";
    $genres_result = mysqli_query($conn, $genres_query);
    ?>
    
    <div class="container py-5">
        <h1 class="mb-4" data-aos="fade-right">Music Genres</h1>
        
        <div class="row">
            <?php while($genre = mysqli_fetch_assoc($genres_result)): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title"><?php echo $genre['genre_name']; ?></h3>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary"><?php echo $genre['music_count']; ?></h4>
                                        <p class="mb-0 small">Songs</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary"><?php echo $genre['video_count']; ?></h4>
                                        <p class="mb-0 small">Videos</p>
                                    </div>
                                </div>
                            </div>
                            <a href="genre.php?id=<?php echo $genre['genre_id']; ?>" class="btn btn-primary mt-4">
                                Explore <?php echo $genre['genre_name']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
}
?>

<?php include 'footer.php'; ?>