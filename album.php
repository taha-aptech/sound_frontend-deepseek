<!-- album.php -->
<?php
include 'header.php';

// Get album ID from URL
$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($album_id > 0) {
    // Single album view
    $album_query = "SELECT * FROM album WHERE album_id = $album_id";
    $album_result = mysqli_query($conn, $album_query);
    $album = mysqli_fetch_assoc($album_result);
    
    if ($album) {
        // Get album's music
        $music_query = "SELECT m.*, a.artist_name, g.genre_name 
                       FROM music m 
                       LEFT JOIN artist a ON m.artist_id = a.artist_id 
                       LEFT JOIN genre g ON m.genre_id = g.genre_id 
                       WHERE m.album_id = $album_id 
                       ORDER BY m.created_at DESC";
        $music_result = mysqli_query($conn, $music_query);
        
        // Get album's videos
        $videos_query = "SELECT v.*, a.artist_name, g.genre_name 
                        FROM video v 
                        LEFT JOIN artist a ON v.artist_id = a.artist_id 
                        LEFT JOIN genre g ON v.genre_id = g.genre_id 
                        WHERE v.album_id = $album_id 
                        ORDER BY v.created_at DESC";
        $videos_result = mysqli_query($conn, $videos_query);
        ?>
        
        <div class="container py-5">
            <!-- Album Header -->
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-md-4">
                    <img src="<?php echo $album['cover_image'] ?: 'images/album/default.jpg'; ?>" 
                         alt="<?php echo $album['album_name']; ?>" 
                         class="img-fluid rounded shadow">
                </div>
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold"><?php echo $album['album_name']; ?></h1>
                    <p class="lead"><i class="fas fa-calendar me-2"></i>Released: <?php echo $album['release_year']; ?></p>
                    <?php if($album['description']): ?>
                        <p class="mt-3"><?php echo $album['description']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Album's Music -->
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4">Tracks</h2>
                    <?php if(mysqli_num_rows($music_result) > 0): ?>
                        <div class="list-group">
                            <?php while($music = mysqli_fetch_assoc($music_result)): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <?php if($music['is_new']): ?>
                                                <span class="badge bg-danger me-3">NEW</span>
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="mb-1"><?php echo $music['title']; ?></h5>
                                                <p class="mb-1 text-muted"><?php echo $music['artist_name']; ?> | <?php echo $music['genre_name']; ?></p>
                                            </div>
                                        </div>
                                        <audio controls class="w-50">
                                            <source src="<?php echo $music['file_path']; ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No music available in this album.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Album's Videos -->
            <div class="row" data-aos="fade-up">
                <div class="col-12">
                    <h2 class="section-title mb-4">Videos</h2>
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
                    <?php else: ?>
                        <p class="text-muted">No videos available for this album.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container py-5 text-center"><h2>Album not found</h2></div>';
    }
} else {
    // Albums listing page
    $albums_query = "SELECT * FROM album ORDER BY release_year DESC, album_name";
    $albums_result = mysqli_query($conn, $albums_query);
    ?>
    
    <div class="container py-5">
        <h1 class="mb-4" data-aos="fade-right">Albums</h1>
        
        <div class="row">
            <?php while($album = mysqli_fetch_assoc($albums_result)): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card album-card h-100">
                        <a href="album.php?id=<?php echo $album['album_id']; ?>" class="text-decoration-none text-dark">
                            <img src="<?php echo $album['cover_image'] ?: 'images/album/default.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo $album['album_name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $album['album_name']; ?></h5>
                                <p class="card-text text-muted">Released: <?php echo $album['release_year']; ?></p>
                                <?php if($album['description']): ?>
                                    <p class="card-text small"><?php echo substr($album['description'], 0, 100); ?>...</p>
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