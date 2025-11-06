<!-- artist.php -->
<?php
include 'header.php';

// Get artist ID from URL
$artist_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($artist_id > 0) {
    // Single artist view
    $artist_query = "SELECT * FROM artist WHERE artist_id = $artist_id";
    $artist_result = mysqli_query($conn, $artist_query);
    $artist = mysqli_fetch_assoc($artist_result);
    
    if ($artist) {
        // Get artist's music
        $music_query = "SELECT m.*, g.genre_name, al.album_name 
                       FROM music m 
                       LEFT JOIN genre g ON m.genre_id = g.genre_id 
                       LEFT JOIN album al ON m.album_id = al.album_id 
                       WHERE m.artist_id = $artist_id 
                       ORDER BY m.created_at DESC";
        $music_result = mysqli_query($conn, $music_query);
        
        // Get artist's videos
        $videos_query = "SELECT v.*, g.genre_name, al.album_name 
                        FROM video v 
                        LEFT JOIN genre g ON v.genre_id = g.genre_id 
                        LEFT JOIN album al ON v.album_id = al.album_id 
                        WHERE v.artist_id = $artist_id 
                        ORDER BY v.created_at DESC";
        $videos_result = mysqli_query($conn, $videos_query);
        ?>
        
        <div class="container py-5">
            <!-- Artist Header -->
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-md-4">
                    <img src="<?php echo $artist['artist_image'] ?: 'images/artist/default.jpg'; ?>" 
                         alt="<?php echo $artist['artist_name']; ?>" 
                         class="img-fluid rounded shadow">
                </div>
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold"><?php echo $artist['artist_name']; ?></h1>
                    <?php if($artist['country']): ?>
                        <p class="lead"><i class="fas fa-map-marker-alt me-2"></i><?php echo $artist['country']; ?></p>
                    <?php endif; ?>
                    <?php if($artist['description']): ?>
                        <p class="mt-3"><?php echo $artist['description']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Artist's Music -->
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4">Music by <?php echo $artist['artist_name']; ?></h2>
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
                                                        <?php echo $music['album_name'] ? 'Album: ' . $music['album_name'] : 'Single'; ?> | 
                                                        <?php echo $music['genre_name']; ?>
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
                    <?php else: ?>
                        <p class="text-muted">No music available for this artist.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Artist's Videos -->
            <div class="row" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4">Videos by <?php echo $artist['artist_name']; ?></h2>
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
                                            <p class="card-text small text-muted">
                                                <?php echo $video['album_name'] ? 'Album: ' . $video['album_name'] : 'Single'; ?> | 
                                                <?php echo $video['genre_name']; ?>
                                            </p>
                                            <a href="<?php echo $video['file_path']; ?>" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-1"></i>Watch
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No videos available for this artist.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container py-5 text-center"><h2>Artist not found</h2></div>';
    }
} else {
    // Artists listing page
    $artists_query = "SELECT * FROM artist ORDER BY artist_name";
    $artists_result = mysqli_query($conn, $artists_query);
    ?>
    
    <div class="container py-5">
        <h1 class="mb-4" data-aos="fade-right">Artists</h1>
        
        <div class="row">
            <?php while($artist = mysqli_fetch_assoc($artists_result)): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up">
                    <div class="card artist-card h-100 text-center">
                        <a href="artist.php?id=<?php echo $artist['artist_id']; ?>" class="text-decoration-none text-dark">
                            <img src="images/<?php echo $artist['artist_image'] ?: 'images/artist/default.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo $artist['artist_name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artist['artist_name']; ?></h5>
                                <?php if($artist['country']): ?>
                                    <p class="card-text text-muted small"><?php echo $artist['country']; ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
}
?>

<?php include 'footer.php'; ?>